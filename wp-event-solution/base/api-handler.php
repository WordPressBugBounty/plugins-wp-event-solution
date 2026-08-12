<?php
namespace Etn\Base;

class Api_Handler {

    public $prefix  = '';
    public $param   = '';
    public $args    = [];
    public $request = null;

    /**
     * constructor function for the class
     * 
     * @return void
     */
    public function __construct() {
        $this->config();
        $this->init();
    }

    /**
     * config can be override by child class
     * 
     * @return void
     */
    public function config() {

    }

    /**
     * rest api pattern buildup process
     * 
     * @return void
     */
    public function init() {
        add_action( 'rest_api_init', function () {
            register_rest_route( untrailingslashit( 'eventin/v1/' . $this->prefix ), '/(?P<action>\w+)/' . ltrim( $this->param, '/' ), [
                'methods'             => \WP_REST_Server::ALLMETHODS,
                'callback'            => [$this, 'callback'],
                'permission_callback' => [ $this, 'permision_check' ],
                // all permissions are implemented inside the callback action
            ] );
        } );
    }

    /**
     * callback function after api endpoint fired
     * 
     * @return void
     */
    public function callback( $request ) {
        $this->request = $request;

        $action_class = strtolower( $this->request->get_method() ) . '_' . $this->request['action'];

        if ( method_exists( $this, $action_class ) ) {
            return $this->{$action_class}();
        }

    }

    /**
     * Actions callable without a WordPress account.
     *
     * Entries are handler names — `{http method}_{action}`, e.g. `get_events`
     * — so the same action can be public for GET and gated for POST.
     *
     * Empty here on purpose: the base class grants nothing. A subclass opts an
     * action in only when it is genuinely meant for anonymous front-end use AND
     * guards the data it returns itself.
     *
     * @return string[]
     */
    protected function public_actions() {
        return [];
    }

    /**
     * Capability required per action, keyed by handler name.
     *
     * An action that appears in neither this map nor public_actions() is not
     * routable at all — see permision_check().
     *
     * @return array<string,string>
     */
    protected function action_capabilities() {
        return [];
    }

    /**
     * Central permission check for every legacy v1 route — deny by default.
     *
     * This class registers ONE catch-all route per subclass
     * (`eventin/v1/<prefix>/(?P<action>\w+)`, ALLMETHODS) and dispatches it to
     * `{method}_{action}()`. That makes this the only gate in front of every
     * action a subclass will ever define, including ones added later.
     *
     * It used to end in `return true`, so authorization rested entirely on each
     * handler remembering to check for itself. Two things made that worse than
     * it sounds:
     *
     * 1. The nonce is not authentication. WordPress issues a valid `wp_rest`
     *    nonce to logged-out visitors (bound to user id 0) and Eventin puts one
     *    on every front-end page (utils/locale/vars.php), so anyone could read
     *    it out of the page source and pass the gate. It proves the request came
     *    from the site, nothing about who sent it.
     * 2. `callback()` dispatches on `method_exists()`, which is also true for
     *    private helpers and for public helpers that take required arguments —
     *    so `GET .../event/seat_plan_details` reached a two-argument helper with
     *    zero arguments and raised an uncaught ArgumentCountError. An anonymous
     *    visitor could trigger a PHP fatal on demand.
     *
     * Now an action must be declared before it can be reached: public, or bound
     * to a capability. Anything undeclared — a helper, a typo, a handler someone
     * adds without thinking about access — is refused.
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return  bool
     */
    public function permision_check( $request ) {
        $nonce = $request->get_header( 'X-WP-Nonce' );

        // CSRF gate, unchanged. Necessary, but never sufficient on its own.
        if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return false;
        }

        $action = isset( $request['action'] ) ? (string) $request['action'] : '';

        if ( '' === $action ) {
            return false;
        }

        // Mirrors how callback() builds the method name, so what we authorise is
        // exactly what will run.
        $handler = strtolower( $request->get_method() ) . '_' . $action;

        /**
         * Filter the actions reachable without a WordPress account.
         *
         * The escape hatch for anything extending this class outside the
         * plugin: without it a third-party subclass would find every action
         * denied after this change.
         *
         * @param string[]     $public  Handler names, e.g. 'get_events'.
         * @param Api_Handler  $handler The handler instance.
         */
        $public = (array) apply_filters( 'eventin_v1_public_actions', $this->public_actions(), $this );

        if ( in_array( $handler, $public, true ) ) {
            return true;
        }

        /**
         * Filter the capability required per action.
         *
         * @param array<string,string> $capabilities Capability keyed by handler name.
         * @param Api_Handler          $handler      The handler instance.
         */
        $capabilities = (array) apply_filters( 'eventin_v1_action_capabilities', $this->action_capabilities(), $this );

        // Undeclared: not public, no capability bound to it. Refuse outright
        // rather than guessing a default — this is what makes a handler added
        // tomorrow closed until someone deliberately opens it.
        if ( empty( $capabilities[ $handler ] ) ) {
            return false;
        }

        // Administrators manage the whole install, so they bypass the specific
        // capability the same way Ownership::is_unscoped() lets them through.
        return current_user_can( 'manage_options' ) || current_user_can( $capabilities[ $handler ] );
    }

}
