<?php
/**
 * Woocommerce Overrides
 *
 * Include any customizations here.
 *
 * @package Scaffolding
 */

/************************************
 * WOOCOMMERCE BUILD
 */

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
function scaffolding_woocommerce_assets() {
	global $post;

	// Enqueue our styles.
	$woo_global_css_version = filemtime( get_theme_file_path( '/css/plugins/woocommerce/global.css' ) );
	wp_enqueue_style( 'scaffolding-woocommerce-global', get_stylesheet_directory_uri() . '/css/plugins/woocommerce/global.css', array(), $woo_global_css_version );

	if ( is_product() || ( ! empty( $post->post_content ) && strstr( $post->post_content, '[product_page' ) ) ) {
		$woo_product_css_version = filemtime( get_theme_file_path( '/css/plugins/woocommerce/product.css' ) );
		wp_enqueue_style( 'scaffolding-woocommerce-product', get_stylesheet_directory_uri() . '/css/plugins/woocommerce/product.css', array(), $woo_product_css_version );
	}

	if ( is_cart() ) {
		$woo_cart_css_version = filemtime( get_theme_file_path( '/css/plugins/woocommerce/cart.css' ) );
		wp_enqueue_style( 'scaffolding-woocommerce-cart', get_stylesheet_directory_uri() . '/css/plugins/woocommerce/cart.css', array(), $woo_cart_css_version );
	}

	if ( is_checkout() || is_order_received_page() ) {
		$woo_checkout_css_version = filemtime( get_theme_file_path( '/css/plugins/woocommerce/checkout.css' ) );
		wp_enqueue_style( 'scaffolding-woocommerce-checkout', get_stylesheet_directory_uri() . '/css/plugins/woocommerce/checkout.css', array(), $woo_checkout_css_version );
	}

	if ( is_account_page() ) {
		$woo_myaccount_css_version = filemtime( get_theme_file_path( '/css/plugins/woocommerce/myaccount.css' ) );
		wp_enqueue_style( 'scaffolding-woocommerce-myaccount', get_stylesheet_directory_uri() . '/css/plugins/woocommerce/myaccount.css', array(), $woo_myaccount_css_version );
	}

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
add_action( 'wp_enqueue_scripts', 'scaffolding_woocommerce_assets', 9999 );

/**
 * Enqueue Gutenberg block assets
 * for editor and front-end
 */
function scaffolding_woocommerce_gutenberg_block_assets() {
	$woo_blocks_css_version = filemtime( get_theme_file_path( '/css/plugins/woocommerce/blocks.css' ) );
	wp_enqueue_style( 'scaffolding-woocommerce-blocks', get_stylesheet_directory_uri() . '/css/plugins/woocommerce/blocks.css', array(), $woo_blocks_css_version );
}
add_action( 'enqueue_block_assets', 'scaffolding_woocommerce_gutenberg_block_assets' );


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
	echo '<div id="inner-content" class="container"><div id="main" class="clearfix" role="main">';
}
add_action( 'woocommerce_before_main_content', 'scaffolding_woocommerce_output_content_wrapper', 10 );

/**
 * Output closing content wrapper.
 */
function scaffolding_woocommerce_output_content_wrapper_end() {
	echo '</div></div>';
}
add_action( 'woocommerce_after_main_content', 'scaffolding_woocommerce_output_content_wrapper_end', 10 );

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
 * Add wrapper div and container class
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
 */




/************************************
 * SINGLE PRODUCT
 */

/**
 * Change number of gallery thumbnails per row
 */
function scaffolding_woocommerce_product_thumbnails_columns() {
	return 5;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'scaffolding_woocommerce_product_thumbnails_columns' );


/************************************
 * CART
 */

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
