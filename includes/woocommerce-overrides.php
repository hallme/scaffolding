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
 */

/**
 * Remove default woo sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Remove wrapper div
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Output opening content wrapper.
 */
function scaffolding_woocommerce_output_content_wrapper() {
	global $sc_layout_class;
	echo '<div id="inner-content" class="container"><div class="row ' . esc_attr( $sc_layout_class['row'] ) . '"><div id="main" class="' . esc_attr( $sc_layout_class['main'] ) . ' clearfix" role="main">';
}
add_action( 'woocommerce_before_main_content', 'scaffolding_woocommerce_output_content_wrapper', 10 );

/**
 * Output closing content wrapper.
 */
function scaffolding_woocommerce_output_content_wrapper_end() {
	echo '</div>';
}
add_action( 'woocommerce_after_main_content', 'scaffolding_woocommerce_output_content_wrapper_end', 10 );

/**
 * Get sidebar template.
 */
function scaffolding_woocommerce_sidebar() {
	get_sidebar();
}
add_action( 'woocommerce_after_main_content', 'scaffolding_woocommerce_sidebar', 15 );

/**
 * Output closing content wrappers.
 */
function scaffolding_woocommerce_output_main_wrapper_end() {
	echo '</div></div>'; // close .row, #inner-content.
}
add_action( 'woocommerce_after_main_content', 'scaffolding_woocommerce_output_main_wrapper_end', 20 );

/**
 * Remove breadcrumbs from being called here.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

/**
 * Add breadcrumbs to be used site-wide.
 */
function scaffolding_woocommerce_breadcrumbs() {
	if ( function_exists( 'woocommerce_breadcrumb' ) && ! is_front_page() ) {
		woocommerce_breadcrumb();
	}
}
add_action( 'scaffolding_after_content_begin', 'scaffolding_woocommerce_breadcrumbs' );

/**
 * Customize breadcrumb args
 *
 * @param array $defaults Array of arguments.
 */
function scaffolding_woocommerce_breadcrumb_defaults( $defaults ) {
	$defaults['wrap_before'] = '<div class="breadcrumb-wrapper clearfix"><nav class="woocommerce-breadcrumb container" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>';
	$defaults['wrap_after']  = '</nav></div>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'scaffolding_woocommerce_breadcrumb_defaults' );

/**
 * Update pagination args to match our theme pagination
 *
 * @see template-parts/pager.php
 * @param array $args Pagination arguments.
 * @return array
 */
function scaffolding_woocommerce_pagination_args( $args ) {
	$args['prev_text'] = '<i class="fa fa-angle-double-left"></i> Previous Page';
	$args['next_text'] = 'Next Page <i class="fa fa-angle-double-right"></i>';
	return $args;
}
add_filter( 'woocommerce_pagination_args', 'scaffolding_woocommerce_pagination_args' );


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
