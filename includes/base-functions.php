<?php
/**
 * Scaffolding Stock Functions
 *
 * Included stock functions. Custom functions go in functions.php to facilitate future updates if necessary.
 *
 * @link https://github.com/hallme/scaffolding
 * @link http://scaffolding.io
 * @link https://codex.wordpress.org/Theme_Development
 *
 * @package Scaffolding
 *
 * Table of Contents
 *
 * 1.0 - Initiating Scaffolding
 * 2.0 - Cleaning Up wp_head
 * 3.0 - Front-End Improvements
 *    3.1 - Add attributes to next post link
 *    3.2 - Add attributes to previous post link
 *    3.3 - Add title attribute to wp_list_pages
 *    3.4 - Allow for blank search
 * 4.0 - Page Navi
 * 5.0 - Custom Login
 *    5.1 - Add styles to login page
 *    5.2 - Change logo link
 *    5.3 - Change alt attribute on logo
 * 6.0 - Visitor UX Functions
 *    6.1 - Remove p tags from images
*/


/************************************
 * 1.0 - INITIATING SCAFFOLDING
 ************************************/

/**
 * Scaffolding Setup
 *
 * All of these functions are defined below or in functions.php.
 *
 * @since Scaffolding 1.0
 */
function scaffolding_build() {
	add_action( 'init', 'scaffolding_head_cleanup' );                                 // launching operation cleanup
	add_filter( 'the_generator', 'scaffolding_rss_version' );                         // remove WP version from RSS
	add_filter( 'wp_head', 'scaffolding_remove_wp_widget_recent_comments_style', 1 ); // remove pesky injected css for recent comments widget
	add_action( 'wp_head', 'scaffolding_remove_recent_comments_style', 1 );           // clean up comment styles in the head
	add_action( 'wp_enqueue_scripts', 'scaffolding_scripts_and_styles', 999 );	      // enqueue base scripts and styles
	scaffolding_add_image_sizes();                                                    // add additional image sizes
	scaffolding_theme_support();                                                      // launching this stuff after theme setup
	add_action( 'widgets_init', 'scaffolding_register_sidebars' );                    // adding sidebars to Wordpress (these are created in functions.php)
	add_filter( 'the_content', 'scaffolding_filter_ptags_on_images' );                // cleaning up random code around images
	add_filter( 'excerpt_more', 'scaffolding_excerpt_more' );                         // cleaning up excerpt
}
add_action( 'after_setup_theme', 'scaffolding_build', 16 );


/************************************
 * 2.0 - CLEANING UP WP_HEAD
 *************************************/

/**
 * Clean up wp_head() output
 *
 * This function is called in scaffolding_build().
 *
 * @since Scaffolding 1.0
 */
function scaffolding_head_cleanup() {
	//remove_action( 'wp_head', 'feed_links_extra', 3 );                         // category feeds
	//remove_action( 'wp_head', 'feed_links', 2 );                               // post and comment feeds
	remove_action( 'wp_head', 'rsd_link' );                                      // EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' );                              // windows live writer
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );        // links for adjacent posts
	remove_action( 'wp_head', 'wp_generator' );                                  // WP version
}

/**
 * Remove WP version from RSS
 *
 * This function is called in scaffolding_build().
 *
 * @since Scaffolding 1.0
 */
function scaffolding_rss_version() {
	return '';
}

/**
 * Remove injected CSS for recent comments widget
 *
 * This function is called in scaffolding_build().
 *
 * @since Scaffolding 1.0
 */
function scaffolding_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

/**
 * Remove injected CSS from recent comments widget
 *
 * This function is called in scaffolding_build().
 *
 * @since Scaffolding 1.0
 */
function scaffolding_remove_recent_comments_style() {
	global $wp_widget_factory;
	if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
}


/************************************
 * 3.0 - FRONT-END IMPROVEMENTS
 *    3.1 - Add attributes to next post link
 *    3.2 - Add attributes to previous post link
 *    3.3 - Add title attribute to wp_list_pages
 *    3.4 - Allow for blank search
*************************************/

/**
 * Add rel and title attribute to next pagination link
 *
 * @since Scaffolding 1.0
 */
function scaffolding_get_next_posts_link_attributes( $attr ) {
	$attr = 'rel="next" title="View the Next Page"';
	return $attr;
}
add_filter( 'next_posts_link_attributes', 'scaffolding_get_next_posts_link_attributes' );

/**
 * Add rel and title attribute to prev pagination link
 *
 * @since Scaffolding 1.0
 */
function scaffolding_get_previous_posts_link_attributes( $attr ) {
	$attr = 'rel="prev" title="View the Previous Page"';
	return $attr;
}
add_filter( 'previous_posts_link_attributes', 'scaffolding_get_previous_posts_link_attributes' );

/**
 * Add page title attribute to wp_list_pages link tags
 *
 * @since Scaffolding 1.0
 */
function scaffolding_wp_list_pages_filter( $output ) {
	$output = preg_replace( '/<a(.*)href="([^"]*)"(.*)>(.*)<\/a>/', '<a$1 title="$4" href="$2"$3>$4</a>', $output );
	return $output;
}
add_filter( 'wp_list_pages', 'scaffolding_wp_list_pages_filter' );

/**
 * Return the search results page even if the query is empty
 *
 * @link http://vinayp.com.np/how-to-show-blank-search-on-wordpress
 *
 * @since Scaffolding 1.0
 */
function scaffolding_make_blank_search( $query ) {
	if ( ! is_admin() ) {
		global $wp_query;
		if ( isset( $_GET['s'] ) && '' == $_GET['s'] ) {  // if search parameter is blank, do not return false
			$wp_query->set( 's', ' ' );
			$wp_query->is_search = true;
		}
		return $query;
	}
}
add_action( 'pre_get_posts', 'scaffolding_make_blank_search' );


/************************************
 4.0 - PAGE NAVI
 ************************************/

/**
 * Numeric Page Navi
 *
 * This is built into the theme by default. Call it using scaffolding_page_navi().
 *
 * @since Scaffolding 1.0
 */
function scaffolding_page_navi( $before = '', $after = '', $query ) {
	$request        = $query->request;
	$posts_per_page = intval( get_query_var( 'posts_per_page' ) );
	$paged          = intval( get_query_var( 'paged' ) );
	$numposts       = $query->found_posts;
	$max_page       = $query->max_num_pages;

	if ( $numposts <= $posts_per_page || $max_page <= 1 ) {
		return;
	}
	
	if ( empty( $paged ) || 0 == $paged ) {
		$paged = 1;
	}

	$pages_to_show         = ( wp_is_mobile() ) ? 2 : 5;
	$pages_to_show_minus_1 = $pages_to_show - 1;
	$half_page_start       = floor( $pages_to_show_minus_1 / 2 );
	$half_page_end         = ceil( $pages_to_show_minus_1 / 2 );
	$start_page            = $paged - $half_page_start;

	if ( $start_page <= 0 ) {
		$start_page = 1;
	}

	$end_page = $paged + $half_page_end;

	if ( ( $end_page - $start_page ) != $pages_to_show_minus_1 ) {
		$end_page = $start_page + $pages_to_show_minus_1;
	}

	if ( $end_page > $max_page ) {
		$start_page = $max_page - $pages_to_show_minus_1;
		$end_page = $max_page;
	}

	if ( $start_page <= 0 ) {
		$start_page = 1;
	}

	echo $before . '<nav class="page-navigation clearfix"><ol class="scaffolding-page-navi clearfix">' . "";

	if ( $start_page > 1 && $pages_to_show < $max_page ) {
		$first_page_text = __( "First", 'scaffolding' );
		echo '<li class="sc-first-link"><a rel="prev" href="' . get_pagenum_link() . '" title="' . $first_page_text . '">' . $first_page_text . '</a></li>';
	}

	if ( $paged > 1 ) {
		echo '<li class="sc-prev-link">';
			previous_posts_link( '<i class="fa fa-angle-double-left"></i> Previous Page' );
		echo '</li>';
	}

	for ( $i = $start_page; $i <= $end_page; $i++ ) {
		if ( $i == $paged ) {
			echo '<li class="sc-current"><span>' . $i . '</span></li>';
		} elseif ( $i == ( $paged - 1 ) ) {
			echo '<li><a rel="prev" href="' . get_pagenum_link( $i ) . '" title="View Page ' . $i . '">' . $i . '</a></li>';
		} elseif ( $i == ( $paged + 1 ) ) {
			echo '<li><a rel="next" href="' . get_pagenum_link( $i ) . '" title="View Page ' . $i . '">' . $i . '</a></li>';
		} else {
			echo '<li><a href="' . get_pagenum_link( $i ) . '" title="View Page ' . $i . '">' . $i . '</a></li>';
		}
	}

	if ( $end_page < $max_page ) {
		echo '<li class="sc-next-link">';
			next_posts_link( 'Next Page <i class="fa fa-angle-double-right"></i>' );
		echo '</li>';
	}

	if ( $end_page < $max_page ) {
		$last_page_text = __( 'Last', 'scaffolding' );
		echo '<li class="sc-last-link"><a rel="next" href="' . get_pagenum_link( $max_page ) . '" title="' . $last_page_text . '">' . $last_page_text . '</a></li>';
	}

	echo '</ol></nav>' . $after . "";

} // end scaffolding_page_navi()


/************************************
 * 5.0 - CUSTOM LOGIN
 *    5.1 - Add styles to login page
 *    5.2 - Change logo link
 *    5.3 - Change alt attribute on logo
*************************************/

/**
 * Custom login page CSS
 *
 * @since Scaffolding 1.0
 */
function scaffolding_login_css() {
	echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/css/login.css">';
}
add_action( 'login_head', 'scaffolding_login_css' );

/**
 * Change logo link from wordpress.org to your site
 *
 * @since Scaffolding 1.0
 */
function scaffolding_login_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'scaffolding_login_url' );

/**
 * Change alt text on logo to show your site name
 *
 * @since Scaffolding 1.0
 */
function scaffolding_login_title() {
	return get_option( 'blogname' );
}
add_filter( 'login_headertitle', 'scaffolding_login_title' );


/************************************
 * 6.0 - VISITOR/USER UX FUNCTIONS
 *    6.1 - Remove p tags from images
*************************************/

/**
 * Remove the p from around imgs
 *
 * This function is called in scaffolding_build().
 *
 * @link http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/
 *
 * @since Scaffolding 1.0
 */
function scaffolding_filter_ptags_on_images( $content ){
	return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
}
