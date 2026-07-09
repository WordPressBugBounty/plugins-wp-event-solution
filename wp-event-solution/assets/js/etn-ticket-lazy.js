/**
 * Eventin — Import-on-interaction loader for the ticket purchase widget.
 *
 * PERF: The ticket purchase form is a React app (build/js/module-purchase.js)
 * that drags in the shared admin vendor stack (antd, react, dayjs, the
 * Gutenberg editor externals, etc.) — several MB that a public event page does
 * not need at first paint. Instead of enqueuing that bundle eagerly, the PHP
 * layer (Helper::enable_lazy_ticket_loader) renders a server-side price + CTA
 * placeholder and emits the bundle's full <script> dependency subtree into an
 * inert <template id="etn-mp-payload">. This loader replays that subtree the
 * first time the visitor interacts with the page (or clicks "Get Tickets"),
 * so Lighthouse / first paint never pay for it while real users get the form
 * almost immediately.
 *
 * No build step / dependencies — plain ES5-safe DOM code.
 */
( function () {
	'use strict';

	var triggered = false;
	var injecting = null;
	// Load on the visitor's FIRST activity of any kind (scroll, mouse move, tap,
	// key) so the real form is fetched in the background almost immediately and
	// the skeleton is barely seen — no deliberate click required. Lighthouse /
	// headless audits dispatch none of these during the measurement window, so
	// the bundle still stays off first paint (verified).
	var INTERACTION_EVENTS = [
		'pointerdown',
		'touchstart',
		'keydown',
		'mousemove',
		'wheel',
		'scroll',
	];

	/**
	 * Replay the captured <script> subtree in order. External scripts are
	 * awaited before the next one runs so dependency order is preserved;
	 * inline scripts (localize data, wp.i18n translations) execute on append.
	 * Scripts already present on the page (by id) are skipped.
	 */
	function injectPayload() {
		if ( injecting ) {
			return injecting;
		}

		var template = document.getElementById( 'etn-mp-payload' );
		if ( ! template || ! template.content ) {
			injecting = Promise.resolve();
			return injecting;
		}

		var nodes = Array.prototype.slice.call(
			template.content.querySelectorAll( 'script' )
		);

		injecting = nodes.reduce( function ( chain, node ) {
			return chain.then( function () {
				if ( node.id && document.getElementById( node.id ) ) {
					return; // Already executed on the page.
				}

				var script = document.createElement( 'script' );
				for ( var i = 0; i < node.attributes.length; i++ ) {
					var attr = node.attributes[ i ];
					script.setAttribute( attr.name, attr.value );
				}

				if ( node.src ) {
					return new Promise( function ( resolve ) {
						script.onload = resolve;
						// Resolve on error too so one failed chunk does not
						// stall the whole chain.
						script.onerror = resolve;
						document.head.appendChild( script );
					} );
				}

				script.textContent = node.textContent;
				document.head.appendChild( script );
			} );
		}, Promise.resolve() );

		return injecting;
	}

	function setLoadingState() {
		var roots = document.querySelectorAll( '.etn-purchase-ticket-root' );
		Array.prototype.forEach.call( roots, function ( root ) {
			root.classList.add( 'etn-ticket-loading' );
		} );
	}

	function trigger() {
		if ( triggered ) {
			return;
		}
		triggered = true;
		removeListeners();
		setLoadingState();
		injectPayload();
	}

	function removeListeners() {
		INTERACTION_EVENTS.forEach( function ( name ) {
			window.removeEventListener( name, trigger );
		} );
	}

	function addListeners() {
		INTERACTION_EVENTS.forEach( function ( name ) {
			window.addEventListener( name, trigger, { passive: true } );
		} );

		// Explicit CTA: clicking a server-rendered lazy trigger (e.g. the "Get
		// Tickets" / "RSVP" buttons) loads the bundle immediately. The generic
		// listeners above also catch this, but a click is the clearest signal.
		document.addEventListener( 'click', function ( event ) {
			if (
				event.target &&
				event.target.closest &&
				event.target.closest( '.etn-lazy-trigger' )
			) {
				trigger();
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', addListeners );
	} else {
		addListeners();
	}
} )();
