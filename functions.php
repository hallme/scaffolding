<?php
/**
 * Scaffolding Main Functions
 *
 * Add custom functions and edit thumbnail sizes, header images, sidebars, menus, etc.
 *
 * @link https://github.com/hallme/scaffolding
 * @link http://scaffolding.io
 * @link https://codex.wordpress.org/Theme_Development
 *
 * @package Scaffolding
 *
 * @todo language support, customizer functions
 *
 * Table of Contents
 *
 * 1.0 - Include Files
 * 2.0 - Scripts & Styles
 * 3.0 - Theme Support
 * 4.0 - Menus & Navigation
 * 5.0 - Images & Headers
 * 6.0 - Sidebars
 * 7.0 - Search Functions
 * 8.0 - Comment Layout
 * 9.0 - Utility Functions
 *    9.1 - Removes […] from read more
 *    9.2 - Modified author post link
 *	  9.3 - Add layout classes
 * 10.0 - Admin Customization
 *    10.1 - Set content width
 *    10.2 - Set image attachment width
 *    10.3 - Disable default dashboard widgets
 *    10.4 - Change name of "Posts" in admin menu
 * 11.0 - Custom/Additional Functions
 */

define( 'SCAFFOLDING_THEME_VERSION', '20171102' );
define( 'SCAFFOLDING_INCLUDE_PATH', dirname(__FILE__) . '/includes/' );

/************************************
 * 1.0 - INCLUDE FILES
 ************************************/

// Add any additional files to include here
require_once( SCAFFOLDING_INCLUDE_PATH . 'base-functions.php' );

if ( function_exists( 'is_woocommerce' ) ) {
	require_once( SCAFFOLDING_INCLUDE_PATH . 'woocommerce-overrides.php' );
}

require_once( SCAFFOLDING_INCLUDE_PATH . 'tinymce-settings.php' );
//require_once( SCAFFOLDING_INCLUDE_PATH . 'theme-guide.php' );

// Gravity Forms Customizations
if ( class_exists( 'GFForms' ) ) {
	require_once( SCAFFOLDING_INCLUDE_PATH . 'gf-customizations.php' );
}


/************************************
 * 2.0 - SCRIPTS & STYLES
 ************************************/

/**
 * Enqueue scripts and styles in wp_head() and wp_footer()
 *
 * This function is called in scaffolding_build() in base-functions.php.
 *
 * @since Scaffolding 1.0
 * @global wp_styles
 */
function scaffolding_scripts_and_styles() {
	// get global variables to add conditional wrappers around styles and scripts
	global $wp_styles;
	global $wp_scripts;
	
	// Dequeue Select2 styles added by ACF
	wp_dequeue_style( 'select2' );

	// Dequeue Select2 script added by ACF
	wp_dequeue_script( 'select2' );

	/**
	 * Add to wp_head()
	 */

	// Main stylesheet
	wp_enqueue_style( 'scaffolding-stylesheet', get_stylesheet_directory_uri() . '/css/style.css', array(), SCAFFOLDING_THEME_VERSION );

	// Font Awesome (icon set) - http://fortawesome.github.io/Font-Awesome/
	wp_enqueue_style( 'scaffolding-font-awesome', get_stylesheet_directory_uri() . '/libs/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );

	// IE-only stylesheet
	/* Not currently in use
	wp_enqueue_style( 'scaffolding-ie-only', get_stylesheet_directory_uri() . '/css/ie.css', array(), SCAFFOLDING_THEME_VERSION );
	$wp_styles->add_data( 'scaffolding-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet
	*/

	// Modernizr - http://modernizr.com/
	wp_enqueue_script( 'scaffolding-modernizr', get_stylesheet_directory_uri() . '/libs/js/custom-modernizr.min.js', array(), '3.2.0', false );

	// Respond - https://github.com/scottjehl/Respond
	wp_enqueue_script( 'scaffolding-respondjs', get_stylesheet_directory_uri() . '/libs/js/respond.min.js', array(), '1.4.2', false );
	$wp_scripts->add_data( 'scaffolding-respondjs', 'conditional', 'lt IE 9' ); // add conditional wrapper around respond script

	/**
	 * Add to wp_footer()
	 */

	// Retina.js - http://imulus.github.io/retinajs/
	wp_enqueue_script( 'scaffolding-retinajs', get_stylesheet_directory_uri() . '/libs/js/retina.min.js', array(), '2.1.1', true );

	// Magnific Popup (lightbox) - http://dimsemenov.com/plugins/magnific-popup/
	wp_enqueue_script( 'scaffolding-magnific-popup-js', get_stylesheet_directory_uri() . '/libs/js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );

	// SelectWoo - https://github.com/woocommerce/selectWoo
	wp_enqueue_script( 'scaffolding-selectwoo', get_stylesheet_directory_uri() . '/libs/js/selectWoo.full.min.js', array( 'jquery' ), '1.0.1', true );

	// Comment reply script for threaded comments
	if ( is_singular() && comments_open() && ( 1 == get_option('thread_comments' ) ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Add Scaffolding scripts file in the footer
	wp_enqueue_script( 'scaffolding-js', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'jquery' ), SCAFFOLDING_THEME_VERSION, true );

} // end scaffolding_scripts_and_styles()


/************************************
 * 3.0 - THEME SUPPORT
 ************************************/

/**
* Add WP3+ functions and theme support
*
* Function called in scaffolding_build() in base-functions.php.
*
* @see scaffolding_custom_headers_callback
* @since Scaffolding 1.0
*/
function scaffolding_theme_support() {

	// Make theme available for translation
	//load_theme_textdomain( 'scaffolding', get_template_directory() . '/languages' );

	// Support for thumbnails
	add_theme_support( 'post-thumbnails' );

	// Support for RSS
	add_theme_support( 'automatic-feed-links' );

	// Support for custom headers
	add_theme_support( 'custom-header', array(
		'default-image'           => '%s/images/headers/default.jpg',
		'random-default'          => false,
		'width'                   => 1140,    // Make sure to set this
		'height'                  => 250,     // Make sure to set this
		'flex-height'             => false,
		'flex-width'              => false,
		'default-text-color'      => 'ffffff',
		'header-text'             => false,
		'uploads'                 => true,
		'wp-head-callback'        => 'scaffolding_custom_headers_callback', // callback function
		'admin-head-callback'     => '',
		'admin-preview-callback'  => '',
		)
	);

	// HTML5
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption',
	) );

	// Title Tag
	add_theme_support( 'title-tag' );

	/*  Feature Currently Disabled
	// WP custom background (thx to @bransonwerner for update)
	add_theme_support( 'custom-background', array(
		'default-color'           => '',      // background color default (dont add the #)
		'default-image'           => '',      // background image default
		'wp-head-callback'        => '_custom_background_cb',
		'admin-head-callback'     => '',
		'admin-preview-callback'  => '',
	) );
	*/

	/* Feature Currently Disabled
	// Support for post formats
	add_theme_support( 'post-formats', array(
			'aside',			// title less blurb
			'gallery',			// gallery of images
			'link',			  	// quick link to other site
			'image',			// an image
			'quote',			// a quick quote
			'status',			// a Facebook like status update
			'video',			// video
			'audio',			// audio
			'chat',				// chat transcript
	) );
	*/

	// Support for menus
	add_theme_support( 'menus' );

	// Register WP3+ menus
	register_nav_menus(
		array(
			'main-nav'   => __( 'Main Menu', 'scaffolding' ),    // main nav in header
			'footer-nav' => __( 'Footer Menu', 'scaffolding' ),   // secondary nav in footer
		)
	);

	// Add styles for use in visual editor
	add_editor_style( 'css/editor-styles.css' );

} // end scaffolding_theme_support()


/************************************
 * 4.0 - MENUS & NAVIGATION
 ************************************/

/**
* Two menus included - main menu in header and footer menu
*
* Add any additional menus here. Register new menu in scaffolding_theme_support() above.
*
* @see scaffolding_walker_nav_menu
* @since Scaffolding 1.0
*/

// Main navigation menu
function scaffolding_main_nav() {
	// Display the wp3 menu if available
	wp_nav_menu( array(
		'container'       => '',						 	  // remove nav container
		'container_class' => '',		 			          // class of container (should you choose to use it)
		'menu'            => '',						      // nav name
		'menu_class'      => 'menu main-menu wrap clearfix',  // adding custom nav class
		'theme_location'  => 'main-nav',			 		  // where it's located in the theme
		'before'          => '',		                      // before the menu
		'after'           => '',						      // after the menu
		'link_before'     => '',						 	  // before each link
		'link_after'      => '',						      // after each link
		'depth'           => 0,							      // limit the depth of the nav
		'fallback_cb'     => '',	                          // fallback function
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'walker'          => new Scaffolding_Walker_Nav_Menu,
	) );
} // end scaffolding_main_nav()

// Footer menu (should you choose to use one)
function scaffolding_footer_nav() {
	wp_nav_menu( array(
		'container'       => '',
		'container_class' => '',
		'menu'            => '',
		'menu_class'      => 'menu footer-menu clearfix',
		'theme_location'  => 'footer-nav',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 0,
		'fallback_cb'     => '__return_false',
	) );
} // end scaffolding_footer_nav()

/**
 * Custom walker to build main navigation menu
 *
 * Adds classes for enhanced styles and support for mobile off-canvas menu.
 *
 * @since Scaffolding 1.0
 */
class Scaffolding_Walker_Nav_Menu extends Walker_Nav_Menu {
	// add classes to ul sub-menus
	function start_lvl( &$output, $depth = 0, $args = Array() ) {
		// depth dependent classes
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1 ); // because it counts the first submenu as 0
		$classes = array(
				'sub-menu',
				( $display_depth % 2 ? 'menu-odd' : 'menu-even' ),
				'menu-depth-' . $display_depth
			);
		$class_names = implode( ' ', $classes );

		// build html
		$output .= "\n" . $indent . '<ul class="' . $class_names . '"><li><a class="menu-back-button" title="Click to Go Back a Menu"><i class="fa fa-chevron-left"></i> Back</a></li>' . "\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';

		// set li classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		if ( ! $args->has_children ) $classes[] = 'menu-item-no-children';

		// combine the class array into a string
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		// set li id
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		// set outer li and its attributes
		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		// set link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : ' title="' . esc_attr( strip_tags( $item->title ) ) . '"';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="'	. esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';

		// Add menu button links to items with children
		if ( $args->has_children ) {
			$menu_pull_link = '<a class="menu-button" title="Click to Open Menu"><i class="fa fa-chevron-right"></i></a>';
		} else {
			$menu_pull_link = '';
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $menu_pull_link.$args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}

   function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

		//Set custom arg to tell if item has children
		$id_field = $this->db_fields['id'];
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}

		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
} // end Scaffolding_Walker_Nav_Menu()


/************************************
 * 5.0 - IMAGES & HEADERS
 ************************************/

/**
 * Add additional image sizes
 *
 * Function called in scaffolding_build() in base-functions.php.
 *
 * Ex. add_image_size( 'scaffolding-thumb-600', 600, 150, true );
 *
 * @since Scaffolding 1.0
 */
function scaffolding_add_image_sizes() {}

/**
 * Register custom image headers
 *
 * @since Scaffolding 1.0
 */
register_default_headers( array(
	'default' => array(
		'url'             => get_template_directory_uri() . '/images/headers/default.jpg',
		'thumbnail_url'   => get_template_directory_uri() . '/images/headers/default.jpg',
		'description'     => __( 'default', 'scaffolding' ),
	)
));

/**
 * Set header image as a BG
 *
 * Includes IE8 polyfill to allow image to span full width of screen.
 * This is a callback function defined in scaffolding_theme_support() 'custom-header'.
 *
 * @since Scaffolding 1.0
 */
function scaffolding_custom_headers_callback() {

	if ( has_header_image() ) {
		?>
		<style type="text/css">
			#banner {
				display: block;
				background-image: url( <?php header_image(); ?> );
				-ms-behavior: url( <?php echo get_template_directory_uri() ?>/includes/backgroundsize.min.htc );
			}
		</style>
		<?php
	}
} // end scaffolding_custom_headers_callback()


/************************************
 * 6.0 - SIDEBARS
 ************************************/

/**
 * Sidebars & Widgets Areas
 *
 * Two sidebars registered - left and right.
 * Define additional sidebars here.
 *
 * @since Scaffolding 1.0
 */
function scaffolding_register_sidebars() {
	register_sidebar(array(
		'id'              => 'left-sidebar',
		'name'            => __('Left Sidebar', 'scaffolding'),
		'description'     => __('The Left (primary) sidebar used for the interior menu.', 'scaffolding'),
		'before_widget'   => '<div id="%1$s" class="widget %2$s">',
		'after_widget'    => '</div>',
		'before_title'    => '<h4 class="widgettitle">',
		'after_title'     => '</h4>',
	));
	register_sidebar(array(
		'id'              => 'right-sidebar',
		'name'            => __('Right Sidebar', 'scaffolding'),
		'description'     => __('The Right sidebar used for the interior call to actions.', 'scaffolding'),
		'before_widget'   => '<div id="%1$s" class="widget %2$s">',
		'after_widget'    => '</div>',
		'before_title'    => '<h4 class="widgettitle">',
		'after_title'     => '</h4>',
	));
} // end scaffolding_register_sidebars()


/************************************
 * 7.0 - SEARCH FUNCTIONS
 ************************************/

/**
 * Filter posts from query that are set to 'noindex'
 *
 * This function is dependent on Yoast SEO Plugin.
 *
 * @since Scaffolding 2.0
 */
function scaffolding_noindex_filter( $query ) {
	if ( ! is_admin() && $query->is_search() && defined( 'WPSEO_VERSION' ) ) {
		$query->set( 'meta_key', '_yoast_wpseo_meta-robots-noindex' );
		$query->set( 'meta_value', '' );
		$query->set( 'meta_compare', 'NOT EXISTS' );
	}
	return $query;
}
add_action( 'pre_get_posts', 'scaffolding_noindex_filter' );


/************************************
 * 8.0 - COMMENT LAYOUT
 ************************************/

/**
 * Comment Layout
 *
 * @since Scaffolding 1.0
 */
function scaffolding_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li'; ?>
	<<?php echo $tag; ?> <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
				<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'], '', get_comment_author() ); ?>
				<?php printf( __( '<cite class="fn">%s</cite>', 'scaffolding' ), get_comment_author_link() ) ?>
				<time datetime="<?php echo comment_time( 'Y-m-d' ); ?>"><a class="comment-date-link" href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time( __( 'F jS, Y', 'scaffolding' ) ); ?> </a> <em><?php edit_comment_link( __( '(Edit)', 'scaffolding'),'  ','' ) ?></em></time>
			</header>
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<div class="alert info">
					<p><?php _e( 'Your comment is awaiting moderation.', 'scaffolding' ); ?></p>
				</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?>
		</article>
	<?php /* </li> or </div> is added by WordPress automatically */ ?>
<?php
} // end scaffolding_comments()


/************************************
 * 9.0 - UTILITY FUNCTIONS
 *     9.1 - Removes […] from read more
 *     9.2 - Modified author post link
 *	   9.3 - Add layout classes
 ************************************/

/**
 * Removes the annoying […] to a Read More link
 *
 * @since Scaffolding 1.0
 * @global post
 */
function scaffolding_excerpt_more( $more ) {
	global $post;
	return '&hellip;  <a class="read-more" href="' . get_permalink( $post->ID ) . '" title="'. __('Read', 'scaffolding') . ' ' . get_the_title( $post->ID ) . '">'. __('Read more &raquo;', 'scaffolding') . '</a>';
} // end scaffolding_excerpt_more()

/**
 * Modifies author post link which just returns the link
 *
 * This is necessary to allow usage of the usual l10n process with printf().
 *
 * @since Scaffolding 1.0
 * @global authordata
 */
function scaffolding_get_the_author_posts_link() {
	global $authordata;
	if ( ! is_object( $authordata ) ) {
		return false;
	}
	$link = sprintf(
		'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
		get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
		esc_attr( sprintf( __( 'Posts by %s', 'scaffolding' ), get_the_author() ) ), // No further l10n needed, core will take care of this one
		get_the_author()
	);
	return $link;
}

/**
 * Set grid classes based on sidebars
 *
 * @since Scaffolding 2.1
 */
function scaffolding_set_layout_classes( $type ) {
	
	if ( '' == $type || ( 'main' != $type && 'sidebar' != $type ) ) {
		return;
	}
	
	if ( 'main' == $type ) {
		
		$class = '';
		
		if ( is_active_sidebar( 'left-sidebar' ) && is_active_sidebar( 'right-sidebar' ) ) { // both sidebars
			$class = 'col-sm-6 col-sm-push-3';
		} elseif ( is_active_sidebar( 'left-sidebar' ) && ! is_active_sidebar( 'right-sidebar' ) ) { // left sidebar
			$class = 'col-sm-9 col-sm-push-3';
		} elseif ( ! is_active_sidebar( 'left-sidebar' ) && is_active_sidebar( 'right-sidebar' ) ) { // right sidebar
			$class = 'col-sm-9';
		} else { // no sidebar
			$class = 'col-xs-12';
		}
		
	} elseif ( 'sidebar' == $type ) {
		
		$class = array();
		
		if ( is_active_sidebar( 'left-sidebar' ) && is_active_sidebar( 'right-sidebar' ) ) { //both sidebars
			$class['left'] = 'col-sm-3 col-sm-pull-6';
			$class['right'] = 'col-sm-3';
		} elseif ( is_active_sidebar( 'left-sidebar' ) && ! is_active_sidebar( 'right-sidebar' ) ) { //left sidebar
			$class['left'] = 'col-sm-3 col-sm-pull-9';
		} elseif ( ! is_active_sidebar( 'left-sidebar' ) && is_active_sidebar( 'right-sidebar' ) ) { //right sidebar
			$class['right'] = 'col-sm-3';
		}
	}
	
	return $class;
}


/************************************
 * 10.0 - ADMIN CUSTOMIZATION
 *     10.1 - Set content width
 *     10.2 - Set image attachment width
 *     10.3 - Disable default dashboard widgets
 *     10.4 - Change name of "Posts" in admin menu
 ************************************/

// Set up the content width value based on the theme's design
if ( ! isset( $content_width ) ) {
	$content_width = 1170;
}

/**
 * Adjust content_width value for image attachment template
 *
 * @since Scaffolding 1.0
 */
function scaffolding_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'scaffolding_content_width' );

/**
 * Disabe Default Dashboard Widgets
 *
 * @since Scaffolding 1.0
 */
function scaffolding_disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// wp..
	//unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );        // Activity
	//unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );       // At a Glance
	//unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] ); // Recent Comments
	//unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );  // Incoming Links
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );         // Quick Press
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );       // Recent Drafts
	//unset( $wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now'] );   // BBPress
	//unset( $wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget''] );          // Yoast SEO
	//unset( $wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard'] );        // Gravity Forms
}
add_action( 'wp_dashboard_setup', 'scaffolding_disable_default_dashboard_widgets', 999 );

/**
 * Change name of "Posts" menu
 *
 * This is useful for improving UX in the WP backend.
 *
 * @since Scaffolding 1.0
 * @global menu, submenu
 */
/*
function scaffolding_change_post_menu_label() {
	global $menu;
	global $submenu;
	$menu[5][0] = 'News';
	$submenu['edit.php'][5][0]  = 'All News Entries';
	$submenu['edit.php'][10][0] = 'Add News Entries';
	$submenu['edit.php'][15][0] = 'Categories'; // Change name for categories
	$submenu['edit.php'][16][0] = 'Tags'; // Change name for tags
	echo '';
}
add_action( 'admin_menu', 'scaffolding_change_post_menu_label' );
*/

/**
 * Change labels for "Posts"
 *
 * This is useful for improving UX in the WP backend.
 *
 * @since Scaffolding 1.0
 * @global wp_post_types
 */
/*
function scaffolding_change_post_object_label() {
	global $wp_post_types;
	$labels                        = &$wp_post_types['post']->labels;
	$labels->name                  = 'News';
	$labels->singular_name         = 'News';
	$labels->add_new               = 'Add News Entry';
	$labels->add_new_item          = 'Add News Entry';
	$labels->edit_item             = 'Edit News Entry';
	$labels->new_item              = 'News Entry';
	$labels->view_item             = 'View Entry';
	$labels->search_items          = 'Search News Entries';
	$labels->not_found             = 'No News Entries found';
	$labels->not_found_in_trash    = 'No News Entries found in Trash';
}
add_action( 'init', 'scaffolding_change_post_object_label' );
*/


/************************************
 * 11.0 CUSTOM/ADDITIONAL FUNCTIONS
 ************************************/

// Add your custom functions here
