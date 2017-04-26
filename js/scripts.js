/******************************************************************
Site Name:
Author:

Name: Scaffolding Scripts

This file should contain any js scripts you want to add to the site.
Instead of calling it in the header or throwing it inside wp_head()
this file will be called automatically in the footer so as not to
slow the page load.

******************************************************************/

// IE8 ployfill for GetComputed Style (for Responsive Script below)
if (!window.getComputedStyle) {
	window.getComputedStyle = function(el, pseudo) {
		this.el = el;
		this.getPropertyValue = function(prop) {
			var re = /(\-([a-z]){1})/g;
			if (prop == 'float') prop = 'styleFloat';
			if (re.test(prop)) {
				prop = prop.replace(re, function () {
					return arguments[2].toUpperCase();
				});
			}
			return el.currentStyle[prop] ? el.currentStyle[prop] : null;
		}
		return this;
	}
}

// Calculate the width of the scroll bar so css media queries and js widow.width match
function getScrollBarWidth () {
	var inner = document.createElement('p');
	inner.style.width = "100%";
	inner.style.height = "200px";

	var outer = document.createElement('div');
	outer.style.position = "absolute";
	outer.style.top = "0px";
	outer.style.left = "0px";
	outer.style.visibility = "hidden";
	outer.style.width = "200px";
	outer.style.height = "150px";
	outer.style.overflow = "hidden";
	outer.appendChild (inner);

	document.body.appendChild (outer);
	var w1 = inner.offsetWidth;
	outer.style.overflow = 'scroll';
	var w2 = inner.offsetWidth;
	if (w1 == w2) w2 = outer.clientWidth;

	document.body.removeChild (outer);

	return (w1 - w2);
};

// As the page loads, call these scripts
jQuery(document).ready(function($) {

	// Select2 - https://select2.github.io/
	if ($.fn.select2) {
		var setup_select2 = function() {
			// Add search only if results exceed number
			var search = 20;
			
			// Disable search on touch devices for cleaner UX
			if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)) {
				search = -1;
			}
			
			$('select').select2({
				minimumResultsForSearch: search,
			});
		};
		$(document).ajaxComplete(setup_select2);
		$(document).bind('gform_post_render', setup_select2);
		setup_select2();
	}

	// Lightbox - http://dimsemenov.com/plugins/magnific-popup/
	if ($.fn.magnificPopup) {
		$image_selector = 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]';

		// single image popup
		$($image_selector).each(function(){
			if ($(this).parents('.gallery').length == 0) {
				$(this).magnificPopup({type:'image'});
			}
		});

		// gallery popup
		$('.gallery').each(function() {
			$(this).magnificPopup({
				type: 'image',
				delegate: $image_selector,
				gallery: {
					enabled: true,
					preload: [1,2]
				},
				image: {
					titleSrc: function(item) {
						return item.el.parents('.gallery-item').find('.gallery-caption').text();
					}
				}
			});
		});
	}

	// Responsive iFrames, Embeds and Objects - http://css-tricks.com/NetMag/FluidWidthVideo/Article-FluidWidthVideo.php
	var $allVideos = $("iframe[src*='youtube'], iframe[src*='hulu'], iframe[src*='revision3'], iframe[src*='vimeo'], iframe[src*='blip'], iframe[src*='dailymotion'], iframe[src*='funnyordie'], object, embed").wrap( "<figure></figure>" ),
	$fluidEl = $("figure");

	$allVideos.each(function() {
		$(this)
		// jQuery .data does not work on object/embed elements
		.attr('data-aspectRatio', this.height / this.width)
		.css({ 'max-width': this.width + 'px', 'max-height': this.height + 'px' })
		.removeAttr('height')
		.removeAttr('width');
	});
	$(window).resize(function() {
		$allVideos.each(function() {
			var $el = $(this);
			var newWidth = $el.closest("figure").width();
			$el
			.width(newWidth)
			.height(newWidth * $el.attr('data-aspectRatio'));
		});
	}).resize();

	// Equal Height Divs - http://css-tricks.com/equal-height-blocks-in-rows/
	equalheight = function(container){

		var currentTallest = 0,
		currentRowStart = 0,
		rowDivs = new Array(),
		$el,
		topPosition = 0;

		$(container).each(function() {
			$el = $(this);
			$($el).height('auto')
			topPostion = $el.position().top;

			if (currentRowStart != topPostion) {
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
					rowDivs[currentDiv].height(currentTallest);
				}
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.height();
				rowDivs.push($el);
			} else {
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
			}
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
		});
	}

	/*
	Responsive jQuery is a tricky thing.
	There's a bunch of different ways to handle
	it, so be sure to research and find the one
	that works for you best.
	*/

	// getting viewport width
	var responsive_viewport = $(window).width() + getScrollBarWidth();

	/*
	Mobile Navigation
	*/
	var menu = $('#main-navigation > ul');

	$(function() {
		$('#mobile-menu-button').on('click', function(e) {
			$('body').toggleClass('menu-open');
		});

		$('#main-navigation .menu-item > .menu-button').on('click', function(e) {
			$(this).next('.sub-menu').addClass('sub-menu-open');
		});

		$('#main-navigation .sub-menu .menu-back-button').on('click', function(e) {
			$(this).parent('li').parent('ul').removeClass('sub-menu-open');
		});

		/*
		Fixes bug on touch devices
		opens ul on first tap
		accepts anchor and opens page on second tap
		*/
		responsive_viewport = $(window).width() + getScrollBarWidth();
		if (responsive_viewport >= 768) {
			$('#main-navigation .menu-item-has-children').doubleTapToGo();
		}
	})

	$(window).resize(function(e) {
		if (Modernizr && Modernizr.touch) {
			e.preventDefault();
		} else {
			responsive_viewport = $(window).width() + getScrollBarWidth();
			if (responsive_viewport >= 768) {
				$('body').removeClass('menu-open');
			} else if (responsive_viewport < 768 && !menu.is(':hidden')) {
				$('body').removeClass('menu-open');
			}
		}
	});
	// end responsive nav

	// if is smaller than 481px
	if (responsive_viewport < 481) {
		// if mobile device and not on the home page scroll to the content on page load
		if (!$('body').hasClass("home")) {
			var new_position = jQuery('#main').offset();
			if (typeof new_position != 'undefined') {
				jQuery('html, body').animate({scrollTop:new_position.top}, 2000);
			}
		}
	}

	// if is larger than 481px
	if (responsive_viewport >= 481){}

	// if is larger than or equal to 768px
	if (responsive_viewport >= 768) {
		// load gravatars
		$('.comment img[data-gravatar]').each(function() {
			$(this).attr('src',$(this).attr('data-gravatar'));
		});
	}

	// if is smaller than 1024px
	if (responsive_viewport < 1024) {}

	// if is larger than or equal to 1024px
	if (responsive_viewport >= 1024) {}

	// hide #back-top first
	$("#back-top").hide();

	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 300) {
				$('#back-top').fadeIn();
			}
			else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

	/*
	// Released under MIT license: http://www.opensource.org/licenses/mit-license.php
	$('[placeholder]').focus(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
			input.val('');
			input.removeClass('placeholder');
		}
	}).blur(function() {
		var input = $(this);
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
			input.addClass('placeholder');
			input.val(input.attr('placeholder'));
		}
	}).blur().parents('form').submit(function() {
		$(this).find('[placeholder]').each(function() {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
			}
		})
	});
	*/

}); /* end of as page load scripts */

/*
By Osvaldas Valutis, www.osvaldas.info
Available for use under the MIT License
Fixes navigation bug on touch devices
*/
;(function( $, window, document, undefined ) {
	$.fn.doubleTapToGo = function(params) {
		if ( ! ( 'ontouchstart' in window ) &&
			! navigator.msMaxTouchPoints &&
			! navigator.userAgent.toLowerCase().match( /windows phone os 7/i ) ) return false;

		this.each( function() {
			var curItem = false;

			$( this ).on( 'click', function(e) {
				var item = $( this );
				if ( item[ 0 ] != curItem[ 0 ] ) {
					e.preventDefault();
					curItem = item;
				}
			});

			$( document ).on( 'click touchstart MSPointerDown', function(e) {
				var resetItem = true,
					parents	  = $( e.target ).parents();

				for ( var i = 0; i < parents.length; i++ )
					if ( parents[ i ] == curItem[ 0 ] )
						resetItem = false;

				if ( resetItem )
					curItem = false;
			});
		});
		return this;
	};
})( jQuery, window, document );

/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w){
	// This fix addresses an iOS bug, so return early if the UA claims it's something else.
	if( !( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( "AppleWebKit" ) > -1 ) ) {
		return;
	}
	var doc = w.document;
	if( !doc.querySelector ) {
		return;
	}
	var meta = doc.querySelector( "meta[name=viewport]" ),
		initialContent = meta && meta.getAttribute( "content" ),
		disabledZoom = initialContent + ",maximum-scale=1",
		enabledZoom = initialContent + ",maximum-scale=10",
		enabled = true,
		x, y, z, aig;
	if( !meta ) {
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
		x = Math.abs( aig.x );
		y = Math.abs( aig.y );
		z = Math.abs( aig.z );
		// If portrait orientation and in one of the danger zones
		if( !w.orientation && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ) {
			if( enabled ){
				disableZoom();
			}
		}
		else if( !enabled ) {
			restoreZoom();
		}
	}
	w.addEventListener( "orientationchange", restoreZoom, false );
	w.addEventListener( "devicemotion", checkTilt, false );
})( this );
