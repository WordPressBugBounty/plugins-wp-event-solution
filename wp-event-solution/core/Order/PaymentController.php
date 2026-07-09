<?php

namespace Eventin\Order;

defined('ABSPATH') || exit;

use Etn\Core\Attendee\Attendee_Model;
use Etn\Core\Event\Event_Model;
use Etn_Surecart_Addon\Integrations\SureCart\SureCart;
use Eventin\Emails\AdminOrderEmail;
use Eventin\Emails\AttendeeOrderEmail;
use Eventin\Integrations\WC\WCPayment;
use Eventin\Mails\Mail;
use Eventin\Settings;
use Eventin_addon_for_FluentCart\Integrations\FluentCart\FluentCartPayment;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;
use EventinPro\Integrations\Stripe\StripePayment;
use EventinPro\Integrations\Paypal\PaypalPayment;

/**
 * Payment controller class
 *
 * @package Eventin
 */
class PaymentController extends WP_REST_Controller
{
	/**
	 * Constructor for PaymentController
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->namespace = 'eventin/v2';
		$this->rest_base = 'payment';
	}

	/**
	 * Check if a given request has access to get items.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|boolean
	 */
	public function register_routes()
	{
		register_rest_route($this->namespace, $this->rest_base, [
			[
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => [$this, 'create_payment'],
				'permission_callback' => [$this, 'create_payment_permission_check'],
			],
			[
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => [$this, 'payment_complete'],
				'permission_callback' => [$this, 'create_payment_permission_check'],
			],
		]);
	}

	/**
	 * Create payment permission check.
	 *
	 * Intentionally nonce-only: payment is initiated by unauthenticated guests as
	 * part of the guest ticket purchase flow. The order already exists at this point
	 * (created via create_item); this endpoint only drives that order through the
	 * payment gateway. The nonce (injected into the page via localized_data_obj.nonce)
	 * provides CSRF protection without requiring a WordPress account. No order data
	 * belonging to other users is readable through this endpoint.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return  bool
	 */
	public function create_payment_permission_check($request)
	{
		return wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest');
	}

	/**
	 * Create payment intents
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return  JSON
	 */
	public function create_payment($request)
	{
		$data            = json_decode($request->get_body(), true);
		$order_id        = ! empty($data['order_id']) ? intval($data['order_id']) : 0;
		$payment_method  = ! empty($data['payment_method']) ? sanitize_text_field($data['payment_method']) : '';

		if ($payment_method == 'sure_cart' && (!class_exists('\SureCart') || !class_exists(SureCart::class))) {
			return new WP_Error('payment_error', __('Please activate SureCart and Eventin Surecart Addon', 'eventin'));
		}

		if ($payment_method == 'fluentcart' && (!class_exists(FluentCartPayment::class) || !class_exists('FluentCart\App\App'))) {
			return new WP_Error('payment_error', 'Please activate FluentCart and Eventin Addon forFluentCart');
		}

		if (($payment_method == 'stripe' || $payment_method == 'paypal') && !class_exists('Wpeventin_Pro')) {
			return new WP_Error('payment_error', __('Please activate Eventin Pro', 'eventin'));
		}

		$payment         = PaymentFactory::get_method($payment_method);
		$order           = new OrderModel($order_id);
		$validate_ticket = $order->validate_ticket(true);

		if (($payment instanceof WCPayment) && !class_exists('WooCommerce')) {
			return new WP_Error('payment_error', __('WooCommerce is not active', 'eventin'));
		}

		if (is_wp_error($validate_ticket)) {
			return new WP_Error('payment_error', $validate_ticket->get_error_message());
		}

		// Native tax: compute geo-matched tax and bake it into total_price BEFORE
		// the gateway reads $order->total_price to build its charge. WooCommerce
		// never reaches here; sure_cart/fluentcart are excluded by name.
		$native_methods = ['stripe', 'paypal', 'free-ticket', 'local_payment'];
		if (in_array($payment_method, $native_methods, true)) {
			$this->apply_native_tax($order);
		}

		$response = $payment->create_payment($order);

		if (is_wp_error($response)) {
			return new WP_Error('payment_error', $response->get_error_message());
		}

		// Update payment id.
		$payment_id = $response['id'] ?? ($response['cart_hash'] ?? '');
		$order->update([
			'payment_id' 		=> $payment_id,
			'payment_method' 	=> $payment_method,
			'currency'			=> etn_currency(),
			'currency_symbol'   => etn_currency_symbol()
		]);

		return rest_ensure_response($response);
	}

	/**
	 * Compute geo-matched tax for a native order and bake it into total_price.
	 *
	 * Stores the uniform native contract:
	 *  - total_price      = grand total charged (subtotal + tax for exclusive;
	 *                       unchanged for inclusive, where tax is already inside)
	 *  - tax_total        = the tax amount (for the itemized line)
	 *  - tax_display_mode = 'incl' (tax is contained in total_price)
	 *
	 * Recomputes the subtotal from live event prices so it is idempotent across
	 * payment retries (never taxes an already-taxed total).
	 *
	 * @param OrderModel $order
	 * @return void
	 */
	private function apply_native_tax($order) {
		$subtotal = $this->native_subtotal($order);

		$settings = [
			'enable_tax' => etn_get_option('enable_tax'),
			'tax_type'   => etn_get_option('tax_type', 'exclusive'),
			'tax_rates'  => etn_get_option('tax_rates', []),
		];

		$event_tax_status = get_post_meta($order->event_id, '_tax_status', true);
		if (! $event_tax_status) {
			$event_tax_status = 'taxable';
		}

		$customer = [
			'country'  => $order->customer_country,
			'state'    => $order->customer_state,
			'postcode' => $order->customer_zip,
			'city'     => $order->customer_city,
		];

		$result       = \Eventin\Order\TaxCalculator::calculate($subtotal, $settings, $event_tax_status, $customer);
		$tax_amount   = floatval($result['taxAmount']);
		$is_inclusive = (isset($settings['tax_type']) ? $settings['tax_type'] : 'exclusive') === 'inclusive';
		$grand_total  = $is_inclusive ? $subtotal : $subtotal + $tax_amount;

		$order->update([
			'total_price'      => $grand_total,
			'tax_total'        => $tax_amount,
			'tax_display_mode' => 'incl',
		]);
	}

	/**
	 * Recompute the pre-tax subtotal: live ticket prices plus the order's stored
	 * add-on (Optiontics) total. Tax is charged on ticket price + option price.
	 *
	 * Ticket prices are re-read from the event for idempotency across payment
	 * retries; options_total is the pre-tax figure frozen at order creation and is
	 * never mutated by {@see apply_native_tax()}, so adding it stays idempotent.
	 *
	 * @param OrderModel $order
	 * @return float
	 */
	private function native_subtotal($order) {
		$event    = new \Etn\Core\Event\Event_Model($order->event_id);
		$subtotal = 0;
		$tickets  = is_array($order->tickets) ? $order->tickets : [];

		foreach ($tickets as $ticket) {
			if (empty($ticket['ticket_slug'])) {
				continue;
			}
			$event_ticket = $event->get_ticket($ticket['ticket_slug']);
			$price        = isset($event_ticket['etn_ticket_price']) ? floatval($event_ticket['etn_ticket_price']) : 0;
			$qty          = isset($ticket['ticket_quantity']) ? intval($ticket['ticket_quantity']) : 0;
			$subtotal    += $price * $qty;
		}

		$subtotal += floatval($order->options_total);

		return $subtotal;
	}

	/**
	 * @title Payment complete
	 * @description
	 * @return JSON
	 */
	public function payment_complete($request)
	{

		$data = json_decode($request->get_body(), true);
		$order_id = !empty($data['order_id']) ? intval($data['order_id']) : 0;
		$payment_status = !empty($data['payment_status']) ? $data['payment_status'] : 0;
		$payment_method = !empty($data['payment_method']) ? $data['payment_method'] : null;
		$order           = new OrderModel($order_id);
		$validate_ticket = $order->validate_ticket(true);

		$temporary_status = 'failed';
		$is_enable_payment_timer = etn_get_option('ticket_purchase_timer_enable', 'off');
		if ($is_enable_payment_timer == 'on') {
			$temporary_status = 'pending';
		}

		// Waiting-list orders are always set to 'pending' by update_payment_method
		// before payment, regardless of the timer setting.
		if (get_post_meta($order_id, 'is_from_waiting_list', true)) {
			$temporary_status = 'pending';
		}

		if (is_wp_error($validate_ticket)) {
			return $validate_ticket;
		}

		if (! in_array($data['payment_method'], ['stripe', 'paypal', 'free-ticket', 'local_payment', 'sure_cart', 'fluentcart'], true)) {
			return new WP_Error('invalid_payment_method', __('Invalid payment method.', 'eventin'), ['status' => 400]);
		}

		$is_admin_order_manager = current_user_can('etn_manage_order') || current_user_can('manage_options');
		if ('local_payment' === $payment_method && ! etn_get_option('local_payment_status') && ! $is_admin_order_manager) {
			return new WP_Error('invalid_payment_method', __('Invalid payment method.', 'eventin'), ['status' => 400]);
		}

		if ('free-ticket' === $data['payment_method']) {
			$order = new OrderModel($order_id);
			if ($order->total_price >  0) {
				return rest_ensure_response([
					'success' => false,
					'message' => __('Payment Update Failed..', 'eventin'),
				]);
			}
		} else {
			// if payment_method stripe
			if ('stripe' === $data['payment_method']) {
				$stripe_transaction_id = $data['stripe_transaction_id'];
				$validation = $this->handle_stripe_validation($stripe_transaction_id, $temporary_status);

				if (is_wp_error($validation)) {
					return rest_ensure_response([
						'success' => false,
						'message' => $validation->get_error_message(),
					]);
				}
			}

			// if payment_method paypal
			if ('paypal' === $data['payment_method']) {
				$paypal_transaction_id = $data['paypal_transaction_id'];
				$validation = $this->handle_paypal_validation($paypal_transaction_id, $temporary_status);

				if (is_wp_error($validation)) {
					return rest_ensure_response([
						'success' => false,
						'message' => $validation->get_error_message(),
					]);
				}
			}

			// if payment_method sure_cart
			if ('sure_cart' === $data['payment_method']) {
				$surecart_checkout_id = !empty($data['surecart_checkout_id']) ? $data['surecart_checkout_id'] : '';
				$validation = $this->handle_surecart_validation($surecart_checkout_id, $temporary_status, $order);

				if (is_wp_error($validation)) {
					return rest_ensure_response([
						'success' => false,
						'message' => $validation->get_error_message(),
					]);
				}
			}
			// if payment_method fluentcart
			if ('fluentcart' === $data['payment_method']) {
				$fluentcart_cart_hash = !empty($data['fluentcart_cart_hash']) ? $data['fluentcart_cart_hash'] : '';
				$validation = $this->handle_fluentcart_validation($fluentcart_cart_hash, $temporary_status, $order);

				if (is_wp_error($validation)) {
					return rest_ensure_response([
						'success' => false,
						'message' => $validation->get_error_message(),
					]);
				}
			}
		}


		$order = new OrderModel($order_id);

		// if payment_method is local_payment
		if ('local_payment' === $payment_method) {
			// Native geo-matched tax: local_payment finalizes through this
			// endpoint (the front-end never calls create_payment for it), so the
			// tax that create_payment bakes in for stripe/paypal would otherwise
			// be skipped here — leaving total_price/tax_total untaxed. Apply it
			// now. apply_native_tax recomputes the subtotal from live prices, so
			// it stays correct across retries.
			$this->apply_native_tax($order);

			$order->update([
				'payment_method' => $payment_method,
				'status'         => 'pending',
			]);

			foreach ($order->get_attendees() as $attendee_data) {
				$attendee = new Attendee_Model($attendee_data['id']);
				$attendee->update(['etn_status' => 'pending']);
			}

			return rest_ensure_response(['success' => true]);  // ← return early
		}

		if ('completed' === $order->status) {
			$response = [
				'success' => true,
				'message' => __('Successfully payment updated', 'eventin'),
			];
			return rest_ensure_response($response);
		}

		if ('success' !== $payment_status) {
			return new WP_Error('payment_error', __('Failed to completed your order', 'eventin'), ['status' => 422]);
		}


		if ('wc' === $order->payment_method && !$this->wc_payment($order_id)) {
			return new WP_Error('payment_error', __('Invalid payment', 'eventin'), ['status' => 422]);
		}

		$order->update([
			'status' => 'completed'
		]);

		do_action('eventin_order_completed', $order);
		$this->send_email($order);

		$response = [
			'success' => true,
			"wc_payment_done" => $this->wc_payment($order_id),
			'message' => __('Successfully payment updated', 'eventin'),
		];

		return rest_ensure_response($response);
	}

	/**
	 * Check wc order is completed
	 *
	 * @param integer $eventin_order_id [$eventin_order_id description]
	 *
	 * @return  bool
	 */
	private function wc_payment($eventin_order_id)
	{
		$post_type = etn_is_enable_wc_synchronize_order() ? 'shop_order' : 'shop_order_placehold';

		$args = [
			'post_type' => $post_type,
			'post_status' => 'any',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'meta_query' => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key' => 'eventin_order_id',
					'value' => $eventin_order_id,
					'compare' => '='
				]
			]
		];


		$orders_ids = get_posts($args);

		if (!$orders_ids) {
			return false;
		}

		$order = wc_get_order($orders_ids[0]);

		if (!$order) {
			return false;
		}

		$statuses = etn_get_wc_order_statuses();

		if (!in_array($order->get_status(), $statuses)) {
			return false;
		}

		return true;
	}

	/**
	 * Send email after payment complete
	 *
	 * @param OrderModel $order [$order description]
	 *
	 * @return  void
	 */
	private function send_email($order)
	{
		$order->send_email();
	}

	/**
	 * Validate that a payment transaction ID is not already used by another order
	 *
	 * @param string $payment_id The payment/transaction ID to validate
	 * @param string $temporary_status The temporary status to exclude from check
	 * @return bool|WP_Error True if valid, WP_Error if duplicate found
	 */
	private function validate_payment_transaction($payment_id, $temporary_status)
	{
		$args = [
			'post_type' => 'etn-order',
			'post_status' => 'draft',
			'posts_per_page' => -1,
			'meta_query' => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key' => 'payment_id',
					'value' => $payment_id,
					'compare' => '='
				],
				[
					'key' => 'status',
					'value' => $temporary_status,
					'compare' => '!='
				]
			]
		];

		$post_query = new \WP_Query($args);
		$total_posts = $post_query->found_posts;

		if ($total_posts) {
			return new WP_Error('duplicate_transaction', __('Unexpected Error', 'eventin'));
		}

		return true;
	}

	/**
	 * Validate Stripe payment
	 *
	 * @param string $stripe_transaction_id The Stripe transaction ID
	 * @param string $temporary_status The temporary status
	 * @return bool|WP_Error True if valid, WP_Error on failure
	 */
	private function handle_stripe_validation($stripe_transaction_id, $temporary_status)
	{
		$validation = $this->validate_payment_transaction($stripe_transaction_id, $temporary_status);

		if (is_wp_error($validation)) {
			return $validation;
		}

		try {
			$response = StripePayment::retrieveIntent($stripe_transaction_id);
		} catch (\Exception $exception) {
			return new WP_Error('stripe_error', __('Unexpected Error', 'eventin'));
		}

		if ($response["status"]["status"] != "succeeded") {
			return new WP_Error('payment_failed', __('Payment Update Failed..', 'eventin'));
		}

		return true;
	}

	/**
	 * Validate PayPal payment
	 *
	 * @param string $paypal_transaction_id The PayPal transaction ID
	 * @param string $temporary_status The temporary status
	 * @return bool|WP_Error True if valid, WP_Error on failure
	 */
	private function handle_paypal_validation($paypal_transaction_id, $temporary_status)
	{
		$validation = $this->validate_payment_transaction($paypal_transaction_id, $temporary_status);

		if (is_wp_error($validation)) {
			return $validation;
		}

		try {
			$paypalPayment = new PaypalPayment();
		} catch (\Exception $exception) {
			return new WP_Error('paypal_error', __('Unexpected Error', 'eventin'));
		}

		$response = $paypalPayment->retrievePaymentCapture($paypal_transaction_id);

		if (!in_array($response["status"]["status"], ["APPROVED", "COMPLETED"])) {
			return new WP_Error('payment_failed', __('Payment Update Failed', 'eventin'));
		}

		return true;
	}

	/**
	 * Validate SureCart payment
	 *
	 * Fails closed: the checkout must be confirmed *paid* by the SureCart API and
	 * must belong to the order being completed. Mirrors handle_stripe_validation()/
	 * handle_paypal_validation(), which retrieve the transaction from the gateway
	 * before trusting a client-supplied identifier.
	 *
	 * This endpoint is nonce-only (guest checkout), so without an upstream check any
	 * visitor could mark an unpaid order completed by posting a fabricated checkout
	 * id (CVE-2026-13039). The local duplicate check alone is not payment proof — it
	 * only blocks reusing the same id twice.
	 *
	 * @param string     $surecart_checkout_id The SureCart checkout ID
	 * @param string     $temporary_status     The temporary status
	 * @param OrderModel $order                The order being completed
	 * @return bool|WP_Error True if valid, WP_Error on failure
	 */
	private function handle_surecart_validation($surecart_checkout_id, $temporary_status, $order)
	{
		if (empty($surecart_checkout_id)) {
			return new WP_Error('missing_checkout_id', __('SureCart checkout ID is required', 'eventin'));
		}

		$validation = $this->validate_payment_transaction($surecart_checkout_id, $temporary_status);

		if (is_wp_error($validation)) {
			return $validation;
		}

		// Can't reach the gateway to verify → refuse to complete the order.
		if (! class_exists(SureCart::class) || ! class_exists('SureCart\Models\ApiToken')) {
			return new WP_Error('payment_unverified', __('Unable to verify SureCart payment', 'eventin'));
		}

		$checkout = ( new SureCart() )->get_checkout_status($surecart_checkout_id);

		if (is_wp_error($checkout) || ! is_array($checkout)) {
			return new WP_Error('payment_unverified', __('Unable to verify SureCart payment', 'eventin'));
		}

		// The checkout must actually be paid (same signal SureCartWebhook trusts).
		$status = isset($checkout['status']) ? $checkout['status'] : '';
		if ('paid' !== $status) {
			return new WP_Error('payment_failed', __('Payment Update Failed..', 'eventin'));
		}

		// And it must be the checkout we created for THIS order (metadata is stamped
		// by create_payment), so a real checkout from another/cheaper order can't be
		// replayed to complete this one.
		$checkout_order_id = isset($checkout['metadata']['eventin_order_id']) ? intval($checkout['metadata']['eventin_order_id']) : 0;
		if (! $checkout_order_id || $checkout_order_id !== intval($order->id)) {
			return new WP_Error('payment_mismatch', __('Payment does not match this order', 'eventin'));
		}

		return true;
	}
	/**
	 * Validate FluentCart payment
	 *
	 * Fails closed: the cart hash must resolve to a FluentCart order whose payment
	 * status is a success status, and that order must reference the Eventin order
	 * being completed. Mirrors the Stripe/PayPal validators — the local duplicate
	 * check alone is not payment proof (CVE-2026-13039).
	 *
	 * @param string     $fluentcart_cart_hash The FluentCart cart hash
	 * @param string     $temporary_status     The temporary status
	 * @param OrderModel $order                The order being completed
	 * @return bool|WP_Error True if valid, WP_Error on failure
	 */
	private function handle_fluentcart_validation($fluentcart_cart_hash, $temporary_status, $order)
	{
		if (empty($fluentcart_cart_hash)) {
			return new WP_Error('missing_cart_hash', __('FluentCart cart hash is required', 'eventin'));
		}

		$validation = $this->validate_payment_transaction($fluentcart_cart_hash, $temporary_status);

		if (is_wp_error($validation)) {
			return $validation;
		}

		// Can't reach FluentCart to verify → refuse to complete the order.
		if (! class_exists('FluentCart\App\Models\Cart') || ! class_exists('FluentCart\App\Helpers\Status')) {
			return new WP_Error('payment_unverified', __('Unable to verify FluentCart payment', 'eventin'));
		}

		$cart = \FluentCart\App\Models\Cart::with('order')->find($fluentcart_cart_hash);
		$fc_order = ($cart && $cart->order) ? $cart->order : null;

		if (! $fc_order) {
			return new WP_Error('payment_unverified', __('Unable to verify FluentCart payment', 'eventin'));
		}

		// The FluentCart order must be paid.
		$success_statuses = \FluentCart\App\Helpers\Status::getOrderPaymentSuccessStatuses();
		if (! in_array($fc_order->payment_status, $success_statuses, true)) {
			return new WP_Error('payment_failed', __('Payment Update Failed..', 'eventin'));
		}

		// And it must be the FluentCart order created for THIS Eventin order — the
		// eventin_order_id is stamped onto every line item's other_info at checkout —
		// so a real paid cart from another/cheaper order can't be replayed here.
		$belongs_to_order = false;
		if (class_exists('FluentCart\App\Models\Order')) {
			$fc_order = \FluentCart\App\Models\Order::with('order_items')->find($fc_order->id);
			if ($fc_order && $fc_order->order_items) {
				foreach ($fc_order->order_items as $item) {
					if (is_array($item->other_info) && isset($item->other_info['eventin_order_id'])
						&& intval($item->other_info['eventin_order_id']) === intval($order->id)) {
						$belongs_to_order = true;
						break;
					}
				}
			}
		}

		if (! $belongs_to_order) {
			return new WP_Error('payment_mismatch', __('Payment does not match this order', 'eventin'));
		}

		return true;
	}
}