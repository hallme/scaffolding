<?php
/**
 * Woocommerce Overrides
 *
 * Include any customizations here.
 *
 * @package Dipstop
 */

/************************************
 * WOOCOMMERCE BUILD
 ************************************/

/**
 * Add theme support
 */
function scaffolding_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'scaffolding_woocommerce_setup' );

/**
 * Remove default styles (we use our own)
 */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/**
 * Enqueue/dequeue assets
 */
function scaffolding_woo_enqueue_assets() {
	// Enqueue our styles.
	$woo_css_version = filemtime( get_theme_file_path( '/css/plugins/woocommerce/style.css' ) );
	wp_enqueue_style( 'spoonful-woo-stylesheet', get_stylesheet_directory_uri() . '/css/plugins/woocommerce/style.css', array(), $woo_css_version );

	// Remove select2 script (we enqueue our own).
	if ( wp_script_is( 'select2', 'enqueued' ) ) {
		wp_dequeue_script( 'select2' );
	}

	// Remove selectWoo script (we enqueue our own).
	if ( wp_script_is( 'selectWoo', 'enqueued' ) ) {
		wp_dequeue_script( 'selectWoo' );
	}

	// Remove select2 style (we enqueue our own).
	// They never changed the stylesheet name to selectWoo.
	if ( wp_style_is( 'select2', 'enqueued' ) ) {
		wp_dequeue_style( 'select2' );
	}
}
add_action( 'wp_enqueue_scripts', 'scaffolding_woo_enqueue_assets', 9999 );


/************************************
 * GLOBAL
 ************************************/

/**
 * Add a custom placeholder image
 */
function scaffolding_woocommerce_placeholder_img_src() {
	return get_stylesheet_directory_uri() . '/images/no-image.jpg'; // update this
}
add_filter( 'woocommerce_placeholder_img_src', 'scaffolding_woocommerce_placeholder_img_src' );

/**
 * Remove default woo sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Remove wrapper div
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

function scaffolding_woocommerce_output_content_wrapper() {
	global $sc_layout_class;
	echo '<div id="inner-content" class="container"><div class="row ' . $sc_layout_class['row'] . '"><div id="main" class="' . $sc_layout_class['main'] . ' clearfix" role="main">';
}
add_action( 'woocommerce_before_main_content', 'scaffolding_woocommerce_output_content_wrapper', 10 );

function scaffolding_woocommerce_output_content_wrapper_end() {
	echo '</div>';
}
add_action( 'woocommerce_after_main_content', 'scaffolding_woocommerce_output_content_wrapper_end', 10 );

function scaffolding_woocommerce_sidebar() {
	get_sidebar();
}
add_action( 'woocommerce_after_main_content', 'scaffolding_woocommerce_sidebar', 15 );

function scaffolding_woocommerce_output_main_wrapper_end() {
	echo '</div></div>'; // close .row, #inner-content
}
add_action( 'woocommerce_after_main_content', 'scaffolding_woocommerce_output_main_wrapper_end', 20 );

/**
 * Remove breadcrumbs from being called here
 * using sitewide, called in header
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
function scaffolding_woocommerce_breadcrumbs() {
	if ( function_exists( 'woocommerce_breadcrumb' ) && ! is_front_page() ) {
		woocommerce_breadcrumb();
	}
}
add_action( 'scaffolding_after_content_begin', 'scaffolding_woocommerce_breadcrumbs' );

/**
 * Customize breadcrumb args
 */
function scaffolding_woocommerce_breadcrumb_defaults( $defaults ) {
	$defaults['wrap_before'] = '<div class="breadcrumb-wrapper clearfix"><nav class="woocommerce-breadcrumb container" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>';
	$defaults['wrap_after']  = '</nav></div>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'scaffolding_woocommerce_breadcrumb_defaults' );

/**
 * Update pagination to use scaffolding for consistency
 */
function scaffolding_woocommerce_pagination() {
	global $wp_query;

	if ( is_tax() ) {
		$display_type = get_woocommerce_term_meta( $wp_query->queried_object_id, 'display_type', true );
	} elseif ( is_shop() ) {
		$display_type = get_option( 'woocommerce_shop_page_display', true );
	} else {
		$display_type = '';
	}

	// apparently pagination does not work on terms/shop with 'subcategories' as the display type
	if ( ( is_tax() || is_shop() ) && $display_type == 'subcategories' ) {
		return;
	} else {
		scaffolding_page_navi( '', '', $wp_query );
	}
}
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'scaffolding_woocommerce_pagination', 10 );


/************************************
 * ARCHIVES
 * Shop and categories
 ************************************/

/**
 * Change number of products per row
 * adds first and last classes to products according to active sidebars
 */
function scaffolding_loop_shop_columns() {
	if ( is_active_sidebar( 'left-sidebar' ) && is_active_sidebar( 'right-sidebar' ) ) {
		return 2;
	} elseif ( is_active_sidebar( 'left-sidebar' ) || is_active_sidebar( 'right-sidebar' ) ) {
		return 3;
	} else {
		return 4;
	}
}
add_filter( 'loop_shop_columns', 'scaffolding_loop_shop_columns', 999 );

/**
 * Add inner wrapper in product li
 */
function scaffolding_woocommerce_open_inner_product() {
	echo '<div class="inner-product">';
}
add_action( 'woocommerce_before_shop_loop_item', 'scaffolding_woocommerce_open_inner_product', 0 );
add_action( 'woocommerce_before_subcategory', 'scaffolding_woocommerce_open_inner_product', 0 );

function scaffolding_woocommerce_close_inner_product() {
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item', 'scaffolding_woocommerce_close_inner_product', 99 );
add_action( 'woocommerce_after_subcategory', 'scaffolding_woocommerce_close_inner_product', 99 );


/************************************
 * SINGLE PRODUCT
 ************************************/

/**
 * Change number of gallery thumbnails per row
 */
function scaffolding_woocommerce_product_thumbnails_columns() {
	return 5;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'scaffolding_woocommerce_product_thumbnails_columns' );


/************************************
 * CART
 ************************************/

/**
 * Change number of crossells
 */
function scaffolding_woocommerce_cross_sells_total() {
	return 2;
}
add_filter( 'woocommerce_cross_sells_total', 'scaffolding_woocommerce_cross_sells_total' );


/************************************
 * CHECKOUT
 */


/************************************
 * MY ACCOUNT
 */
