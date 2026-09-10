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

	// Script `type` values the browser actually executes. A delay-JS optimizer
	// (WP Hummingbird's "wphb-delay-type", WP Rocket's "rocketlazyloadscript",
	// LiteSpeed's "litespeed/javascript", Perfmatters/Flying Scripts, etc.)
	// rewrites every <script type> in the DOM — including the ones parked inside
	// our <template id="etn-mp-payload"> — to an inert placeholder so nothing
	// runs before user interaction. The optimizer only restores placeholders
	// that were in the DOM at load; scripts we clone out of the template later
	// keep the placeholder type and never execute, so the purchase form stays
	// stuck on its loading skeleton. We strip any non-executable type on replay
	// so the cloned scripts run as normal classic/module scripts.
	var EXECUTABLE_SCRIPT_TYPES = {
		'': true,
		'text/javascript': true,
		'application/javascript': true,
		'text/ecmascript': true,
		'application/ecmascript': true,
		'text/jscript': true,
		'module': true,
	};

	function isExecutableScriptType( type ) {
		return EXECUTABLE_SCRIPT_TYPES[ String( type || '' ).toLowerCase() ] === true;
	}

	/**
	 * Kick off the download of every external payload script in parallel via
	 * <link rel="preload">. Execution still happens serially in injectPayload()
	 * to preserve dependency order, but without this the browser would not even
	 * START fetching chunk N until chunk N-1 finished executing — turning six+
	 * vendor bundles into a serial download waterfall (~2s). Preloading warms
	 * the HTTP cache concurrently so each serial <script> resolves almost
	 * immediately, collapsing the waterfall to roughly the slowest single fetch.
	 * Preload does not execute the script, so dependency order is untouched.
	 */
	function preloadPayload( nodes ) {
		if ( ! document.head ) {
			return;
		}
		nodes.forEach( function ( node ) {
			if ( ! node.src ) {
				return; // Inline scripts have nothing to fetch.
			}
			if ( node.id && document.getElementById( node.id ) ) {
				return; // Already on the page.
			}
			var link = document.createElement( 'link' );
			link.rel = 'preload';
			link.as = 'script';
			link.href = node.src;
			// crossOrigin must match how the <script> fetches or the preload is
			// wasted (double download). Scripts here are same-origin/anonymous.
			if ( node.crossOrigin ) {
				link.crossOrigin = node.crossOrigin;
			}
			document.head.appendChild( link );
		} );
	}

	// How long to wait for a delay-JS optimizer to un-park the core globals the
	// bundle needs before giving up and un-parking them ourselves.
	var REACT_WAIT_TIMEOUT = 8000;

	// How long to wait for our own un-parked copies to finish loading.
	var REACT_UNPARK_TIMEOUT = 5000;

	/**
	 * The core globals module-purchase.js resolves its webpack externals
	 * against, paired with the script handle that defines each one. React is not
	 * the only one that can go missing: the bundle also externalises
	 * window.wp.element (forwardRef/createElement) and window.wp.domReady, and
	 * either being undefined kills it just as dead.
	 */
	var CORE_GLOBALS = [
		{
			id: 'react-js',
			ready: function () {
				return !! window.React;
			},
		},
		{
			id: 'wp-element-js',
			ready: function () {
				return !! ( window.wp && window.wp.element );
			},
		},
		{
			id: 'wp-dom-ready-js',
			ready: function () {
				return !! ( window.wp && window.wp.domReady );
			},
		},
	];

	/**
	 * Core WordPress scripts sitting on the page with a non-executable type,
	 * i.e. held by a delay-JS optimizer.
	 *
	 * @return {Array} Parked <script> nodes we have not replayed yet.
	 */
	function getParkedCoreScripts() {
		var parked = document.querySelectorAll(
			'script[src*="/wp-includes/js/dist/"]'
		);

		return Array.prototype.filter.call( parked, function ( node ) {
			return (
				! isExecutableScriptType( node.getAttribute( 'type' ) ) &&
				! node.getAttribute( 'data-etn-unparked' )
			);
		} );
	}

	/**
	 * Force-run core WordPress scripts that a delay-JS optimizer has parked on
	 * the page (outside our template). Last resort: only called when a required
	 * global is still missing after REACT_WAIT_TIMEOUT, at which point the
	 * alternative is a guaranteed crash.
	 */
	function unparkPageScripts() {
		var unparked = 0;
		Array.prototype.forEach.call( getParkedCoreScripts(), function ( node ) {
			node.setAttribute( 'data-etn-unparked', '1' );
			unparked++;
			var script = document.createElement( 'script' );
			for ( var i = 0; i < node.attributes.length; i++ ) {
				var attr = node.attributes[ i ];
				if ( attr.name.toLowerCase() === 'type' ) {
					continue;
				}
				// Never reuse the id — it would collide with the parked twin
				// and make our own "already on the page" check skip work.
				if ( attr.name.toLowerCase() === 'id' ) {
					continue;
				}
				script.setAttribute( attr.name, attr.value );
			}
			script.async = false; // Preserve execution order.
			document.head.appendChild( script );
		} );

		return unparked;
	}

	/**
	 * Poll until every entry in `pending` reports ready, or the budget runs out.
	 *
	 * @param {Array}  pending Entries from CORE_GLOBALS still unaccounted for.
	 * @param {number} budget  Milliseconds to keep waiting.
	 * @return {Promise} Resolves with true if all appeared, false on timeout.
	 */
	function pollForGlobals( pending, budget ) {
		function allReady() {
			return pending.every( function ( entry ) {
				return entry.ready();
			} );
		}

		return new Promise( function ( resolve ) {
			if ( allReady() ) {
				resolve( true );
				return;
			}
			var waited = 0;
			var step = 50;
			var timer = setInterval( function () {
				waited += step;
				if ( allReady() ) {
					clearInterval( timer );
					resolve( true );
					return;
				}
				if ( waited >= budget ) {
					clearInterval( timer );
					resolve( false );
				}
			}, step );
		} );
	}

	/**
	 * Guarantee the bundle's core externals exist before the payload is replayed.
	 *
	 * capture_lazy_script_payload() builds the payload from a clone of
	 * WP_Scripts and therefore SKIPS any handle already printed on the page.
	 * When another plugin (Elementor / Metform, typically) has already printed
	 * core react / react-dom / wp-element, they are omitted from our template —
	 * but a delay-JS optimizer may still be holding those outer copies parked.
	 * Replaying the bundle in that window resolves webpack's `window["React"]`
	 * or `window.wp.element` external to undefined and throws
	 * "Cannot read properties of undefined (reading 'createContext'/'forwardRef')",
	 * leaving the purchase form dead.
	 */
	function ensureCoreGlobalsAvailable( nodes ) {
		var payloadIds = {};
		nodes.forEach( function ( node ) {
			if ( node.id ) {
				payloadIds[ node.id ] = true;
			}
		} );

		// Only wait on globals the payload does NOT carry itself and that are
		// not already live — otherwise every page would pay the poll budget.
		var pending = CORE_GLOBALS.filter( function ( entry ) {
			return ! payloadIds[ entry.id ] && ! entry.ready();
		} );

		// Nothing to wait for: either the payload brings them or they are live.
		if ( ! pending.length ) {
			return Promise.resolve();
		}

		// A global is missing AND no optimizer is holding core scripts back, so
		// waiting cannot make it appear — replay immediately rather than stalling
		// the form for REACT_WAIT_TIMEOUT on a site with no delay-JS plugin.
		if ( ! getParkedCoreScripts().length ) {
			return Promise.resolve();
		}

		// First, give the optimizer a chance to un-park them on its own.
		return pollForGlobals( pending, REACT_WAIT_TIMEOUT ).then( function ( found ) {
			if ( found ) {
				return;
			}
			// Last resort: run the parked core scripts ourselves. Appending a
			// <script> only STARTS the fetch, so keep polling afterwards —
			// resolving here would hand the replay chain a still-undefined
			// global and cause the very crash this guard prevents.
			if ( ! unparkPageScripts() ) {
				return; // Nothing was parked; waiting longer cannot help.
			}
			return pollForGlobals( pending, REACT_UNPARK_TIMEOUT );
		} );
	}

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

		// Start every external download at once; the serial loop below then
		// executes them in order against an already-warm cache.
		preloadPayload( nodes );

		// Downloads above are already in flight; this only gates EXECUTION, so
		// waiting here costs nothing on a site with no delay-JS optimizer.
		injecting = nodes.reduce( function ( chain, node ) {
			return chain.then( function () {
				if ( node.id && document.getElementById( node.id ) ) {
					return; // Already executed on the page.
				}

				var script = document.createElement( 'script' );
				for ( var i = 0; i < node.attributes.length; i++ ) {
					var attr = node.attributes[ i ];
					// Drop a delay-JS placeholder type (e.g. "wphb-delay-type")
					// so the browser executes the replayed script instead of
					// treating it as an unknown, inert type.
					if (
						attr.name.toLowerCase() === 'type' &&
						! isExecutableScriptType( attr.value )
					) {
						continue;
					}
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
		}, ensureCoreGlobalsAvailable( nodes ) );

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
