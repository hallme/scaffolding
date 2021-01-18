/**
 * Site Name:
 * Author:
 *
 * Name: Scaffolding Scripts
 *
 * @package scaffolding
 *
 * This file should contain any js scripts you want to add to the site.
 * Instead of calling it in the header or throwing it inside wp_head()
 * this file will be called automatically in the footer so as not to
 * slow the page load.
 */

// Calculate the width of the scroll bar so css media queries and js widow.width match.
function getScrollBarWidth () {
	var inner          = document.createElement( 'p' );
	inner.style.width  = "100%";
	inner.style.height = "200px";

	var outer              = document.createElement( 'div' );
	outer.style.position   = "absolute";
	outer.style.top        = "0px";
	outer.style.left       = "0px";
	outer.style.visibility = "hidden";
	outer.style.width      = "200px";
	outer.style.height     = "150px";
	outer.style.overflow   = "hidden";
	outer.appendChild( inner );

	document.body.appendChild( outer );
	var w1               = inner.offsetWidth;
	outer.style.overflow = 'scroll';
	var w2               = inner.offsetWidth;
	if (w1 == w2) {
		w2 = outer.clientWidth;
	}

	document.body.removeChild( outer );

	return (w1 - w2);
};

// As the page loads, call these scripts.
jQuery( document ).ready(
	function($) {

		// Initialize Retinajs - https://github.com/strues/retinajs .
		if (jQuery.fn.retinajs) {
			retinajs();
		}

		// SelectWoo - https://github.com/woocommerce/selectWoo .
		if ($.fn.selectWoo) {
			var setup_selectWoo = function() {
				$( 'select' ).not('#rating').each( function(){
					$( this ).selectWoo({
						width: 'null',
					});
				});
			};
			$( document ).ajaxComplete( setup_selectWoo );
			$( document ).bind( 'gform_post_render', setup_selectWoo );
			setup_selectWoo();
		}

		// Lightbox - http://dimsemenov.com/plugins/magnific-popup/ .
		if ($.fn.magnificPopup) {
			$image_selector = 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]';

			// single image popup.
			$( $image_selector ).each(
				function(){
					if ($( this ).parents( '.gallery' ).length == 0) {
						$( this ).magnificPopup( {type:'image'} );
					}
				}
			);

			// gallery popup.
			$( '.gallery' ).each(
				function() {
					$( this ).magnificPopup(
						{
							type: 'image',
							delegate: $image_selector,
							gallery: {
								enabled: true,
								preload: [1,2]
							},
							image: {
								titleSrc: function(item) {
									return item.el.parents( '.gallery-item' ).find( '.gallery-caption' ).text();
								}
							}
						}
					);
				}
			);
		}

		// hide #back-top first.
		$( "#back-top" ).hide();

		// fade in #back-top.
		$(
			function () {
				$( window ).scroll(
					function () {
						if ($( this ).scrollTop() > 300) {
							$( '#back-top' ).fadeIn();
						} else {
							$( '#back-top' ).fadeOut();
						}
					}
				);

				// scroll body to 0px on click.
				$( '#back-top a' ).click(
					function () {
						$( 'body,html' ).animate(
							{
								scrollTop: 0
							},
							800
						);
						return false;
					}
				);
			}
		);

	}
); /* end of as page load scripts */

/*!
 * A fix for the iOS orientationchange zoom bug.
 * Script by @scottjehl, rebound by @wilto.
 * MIT License.
 */
(function(w){
	// This fix addresses an iOS bug, so return early if the UA claims it's something else.
	if ( ! ( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( "AppleWebKit" ) > -1 ) ) {
		return;
	}
	var doc = w.document;
	if ( ! doc.querySelector ) {
		return;
	}
	var meta           = doc.querySelector( "meta[name=viewport]" ),
		initialContent = meta && meta.getAttribute( "content" ),
		disabledZoom   = initialContent + ",maximum-scale=1",
		enabledZoom    = initialContent + ",maximum-scale=10",
		enabled        = true,
		x, y, z, aig;
	if ( ! meta ) {
		return;
	}
	function restoreZoom() {
		meta.setAttribute( "content", enabledZoom );
		enabled = true;
	}
	function disableZoom() {
		meta.setAttribute( "content", disabledZoom );
		enabled = false;
	}
	function checkTilt( e ) {
		aig = e.accelerationIncludingGravity;
		x   = Math.abs( aig.x );
		y   = Math.abs( aig.y );
		z   = Math.abs( aig.z );
		// If portrait orientation and in one of the danger zones.
		if ( ! w.orientation && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ) {
			if ( enabled ) {
				disableZoom();
			}
		} else if ( ! enabled ) {
			restoreZoom();
		}
	}
	w.addEventListener( "orientationchange", restoreZoom, false );
	w.addEventListener( "devicemotion", checkTilt, false );
})( this );
