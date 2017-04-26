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
 * @since Scaffolding 1.0
 *
 * Table of Contents
 *
 * 1.0 - Initiating Scaffolding
 * 2.0 - Cleaning Up wp_head
 * 3.0 - Front-End Improvements
 *    3.1 - Add classes to menus
 *    3.2 - Add classes to widgets
 *    3.3 - Add classes to posts
 *    3.4 - Add attributes to next post link
 *    3.5 - Add attributes to previous post link
 *    3.6 - Add title attribute to wp_list_pages
 *    3.7 - Allow for blank search
 * 4.0 - Page Navi
 * 5.0 - Custom Login
 *    5.1 - Add styles to login page
 *    5.2 - Change logo link
 *    5.3 - Change alt attribute on logo
 * 6.0 - Visitor UX Functions
 *    6.1 - Remove p tags from images
 *    6.2 - Filter hard coded dimensions on captions
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
 *    3.1 - Add classes to menus
 *    3.2 - Add classes to widgets
 *    3.3 - Add classes to posts
 *    3.4 - Add attributes to next post link
 *    3.5 - Add attributes to previous post link
 *    3.6 - Add title attribute to wp_list_pages
 *    3.7 - Allow for blank search
*************************************/

/**
 * Add first and last menu classes
 *
 * This now works with nested uls.
 *
 * @since Scaffolding 1.0
 */
function scaffolding_first_last_menu_classes( $objects, $args ) {

	// Add first/last classes to nested menu items.
	$ids        = array();
	$parent_ids = array();
	$top_ids    = array();

	if ( ! empty( $objects ) ) {

		foreach ( $objects as $i => $object ) {
			// If there is no menu item parent, store the ID and skip over the object.
			if ( 0 == $object->menu_item_parent ) {
				$top_ids[ $i ] = $object;
				continue;
			}

			// Add first item class to nested menus.
			if ( ! in_array( $object->menu_item_parent, $ids ) ) {
				$objects[ $i ]->classes[] = 'first-item';
				$ids[] = $object->menu_item_parent;
			}

			// If we have just added the first menu item class, skip over adding the ID.
			if ( in_array( 'first-item', $object->classes ) ) {
				continue;
			}

			// Store the menu parent IDs in an array.
			$parent_ids[ $i ] = $object->menu_item_parent;
		}

		// Remove any duplicate values and pull out the last menu item.
		$sanitized_parent_ids = array_unique( array_reverse( $parent_ids, true ) );

		// Loop through the IDs and add the last menu item class to the appropriate objects.
		foreach ( $sanitized_parent_ids as $i => $id ) {
			$objects[ $i ]->classes[] = 'last-item';
		}

		// Finish it off by adding classes to the top level menu items.
		$objects[1]->classes[] = 'first-item'; // We can be assured 1 will be the first item in the menu. :-)
		$keys = array_keys( $top_ids );
		$objects[end( $keys )]->classes[] = 'last-item';

		// Return the menu objects.
		return $objects;
	}
}
add_filter( 'wp_nav_menu_objects', 'scaffolding_first_last_menu_classes', 10, 2 );

/**
 * Add classes on widgets
 *
 * Add first and last classes to dynamic sidebar widgets. Also adds numeric index class for each widget (widget-1, widget-2, etc.)
 *
 * @since Scaffolding 1.0
 */
function scaffolding_widget_classes( $params ) {

	global $my_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets

	if ( ! $my_widget_num ) {// If the counter array doesn't exist, create it
		$my_widget_num = array();
	}

	if ( ! isset( $arr_registered_widgets[ $this_id ] ) || ! is_array( $arr_registered_widgets[ $this_id ] ) ) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}

	if ( isset( $my_widget_num[ $this_id ] ) ) { // See if the counter array has an entry for this sidebar
		$my_widget_num[ $this_id ] ++;
	} else { // If not, create it starting with 1
		$my_widget_num[ $this_id ] = 1;
	}

	$class = 'class="widget-' . $my_widget_num[ $this_id ] . ' '; // Add a widget number class for additional styling options

	if ( 1 == $my_widget_num[ $this_id ] && $my_widget_num[ $this_id ] == count( $arr_registered_widgets[ $this_id ] ) ) { // If this is the first widget
		$class .= 'only-widget ';
	} elseif ( 1 == $my_widget_num[ $this_id ] && $my_widget_num[ $this_id ] != count( $arr_registered_widgets[ $this_id ] ) ) {
		$class .= 'first-widget ';
	} elseif ( $my_widget_num[ $this_id ] == count( $arr_registered_widgets[ $this_id ] ) ) { // If this is the last widget
		$class .= 'last-widget ';
	}

	$params[0]['before_widget'] = str_replace( 'class="', $class, $params[0]['before_widget'] ); // Insert our new classes into "before widget"

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'scaffolding_widget_classes' );

/**
 * Add first and last classes to posts
 *
 * Useful on archive and search pages.
 *
 * @since Scaffolding 1.0
 */
function scaffolding_post_classes( $classes ) {
	global $wp_query;
	if ( 0 == $wp_query->current_post && 1 == $wp_query->post_count ) {
		$classes[] = 'only-post';
	} elseif ( 0 == $wp_query->current_post && 1 != $wp_query->post_count ) {
		$classes[] = 'first-post';
	} elseif ( ( 1 + $wp_query->current_post ) == $wp_query->post_count ) {
		$classes[] = 'last-post';
	}

	return $classes;
}
add_filter( 'post_class', 'scaffolding_post_classes' );

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
 *    6.2 - Filter hard coded dimensions on captions
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

/**
 * Filter out hard-coded width, height attributes on all captions (wp-caption class)
 *
 * @since Scaffolding 1.0
 */
function scaffolding_fix_img_caption_shortcode( $attr, $content = null ) {
	if ( ! isset( $attr['caption'] ) ) {
		if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
			$content = $matches[1];
			$attr['caption'] = trim( $matches[2] );
		}
	}
	$output = apply_filters( 'img_caption_shortcode', '', $attr, $content );
	if ( $output != '' ) return $output;
	extract( shortcode_atts( array(
		'id' 		=> '',
		'align' 	=> 'alignnone',
		'width' 	=> '',
		'caption' 	=> ''
	), $attr ) );
	if ( 1 > (int) $width || empty( $caption ) ) return $content;
	if ( $id ) $id = 'id="' . esc_attr( $id ) . '" ';
	return '<figure ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >' . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $caption . '</figcaption></figure>';
}
//add_shortcode( 'wp_caption', 'scaffolding_fix_img_caption_shortcode' );
//add_shortcode( 'caption', 'scaffolding_fix_img_caption_shortcode' );