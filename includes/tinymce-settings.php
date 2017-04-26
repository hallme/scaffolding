<?php
/**
 * Customize the wysiwyg editor
 *
 * These functions also handle backwards compatibility with WordPress as TinyMCE 3 and TinyMCE 4
 * handle this differently.
 *
 * http://www.kevinleary.net/customizing-tinymce-wysiwyg-editor-wordpress/
 * https://shellcreeper.com/complete-guide-to-style-format-drop-down-in-wp-editor/
 */

/**
 * TinyMCE: First line toolbar customizations
 * There are 4 total lines that may be added
 */
function scaffolding_tinymce_modify_mce_buttons_1 ( $buttons ) {
	// The settings are returned in this array. Customize to suite your needs.
	return array(
		'bold', 'italic', 'strikethrough', 'bullist', 'numlist', 'blockquote', 'hr',
		'alignleft', 'aligncenter', 'alignright', 'link', 'unlink', 'wp_more',
		'spellchecker', 'wp_fullscreen', 'wp_adv'
	);
	/* WordPress Default
	return array(
		'bold', 'italic', 'strikethrough', bullist', 'numlist', 'blockquote', 'hr',
		'alignleft', 'aligncenter', 'alignright', 'link', 'unlink', 'wp_more',
		'spellchecker', 'wp_fullscreen', 'wp_adv'
	); */
}
add_filter( 'mce_buttons', 'scaffolding_tinymce_modify_mce_buttons_1', 0 );

/**
 * TinyMCE: Second line toolbar customizations
 * There are 4 total lines that may be added
 */
function scaffolding_tinymce_modify_mce_buttons_2 ( $buttons ) {
	// The settings are returned in this array. Customize to suite your needs.
	return array(
		'styleselect', 'underline', 'alignull', 'forecolor', 'pastetext', 'pasteword', 'removeformat', 'media', 'charmap',
		'outdent', 'indent', 'undo', 'redo', 'wp_help'
	);
	/* WordPress Default
	return array(
		'formatselect', 'underline', 'alignfull', 'pastetext', 'pasteword', 'removeformat', 'charmap',
		'outdent', 'indent', 'undo', 'redo', 'wp_help'
	); */
}
add_filter( 'mce_buttons_2', 'scaffolding_tinymce_modify_mce_buttons_2', 0 );

/**
 * TinyMCE: Modify 'styleselect'
 * This is the Formats dropdown
 */
function scaffolding_tinymce_modify_styleselect( $settings ) {
	
	// Modify default styles
    $default_styles = array(
        array(
            'title'   => 'Headings',
            'items' => array(
                /*
				array(
                    'title'   => 'Heading 1',
                    'format'  => 'h1',
                ),
				*/
                array(
                    'title'   => 'Heading 2',
                    'format'  => 'h2',
                ),
                array(
                    'title'   => 'Heading 3',
                    'format'  => 'h3',
                ),
                array(
                    'title'   => 'Heading 4',
                    'format'  => 'h4',
                ),
                array(
                    'title'   => 'Heading 5',
                    'format'  => 'h5',
                ),
				/*
                array(
                    'title'   => 'Heading 6',
                    'format'  => 'h6',
                ),
				*/
            ),
        ),
        array(
            'title'   => 'Inline',
            'items' => array(
                array(
                    'title'   => 'Bold',
                    'format'  => 'bold',
                    'icon'    => 'bold',
                ),
                array(
                    'title'   => 'Italic',
                    'format'  => 'italic',
                    'icon'    => 'italic',
                ),
                array(
                    'title'   => 'Underline',
                    'format'  => 'underline',
                    'icon'    => 'underline',
                ),
                array(
                    'title'   => 'Strikethrough',
                    'format'  => 'strikethrough',
                    'icon'    => 'strikethrough',
                ),
                array(
                    'title'   => 'Superscript',
                    'format'  => 'superscript',
                    'icon'    => 'superscript',
                ),
                array(
                    'title'   => 'Subscript',
                    'format'  => 'subscript',
                    'icon'    => 'subscript',
                ),
                array(
                    'title'   => 'Code',
                    'format'  => 'code',
                    'icon'    => 'code',
                ),
            ),
        ),
        array(
            'title'   => 'Blocks',
            'items' => array(
                array(
                    'title'   => 'Paragraph',
                    'format'  => 'p',
                ),
                array(
                    'title'   => 'Blockquote',
                    'format'  => 'blockquote',
                ),
                array(
                    'title'   => 'Div',
                    'format'  => 'div',
                ),
                array(
                    'title'   => 'Pre',
                    'format'  => 'pre',
                ),
            ),
        ),
        array(
            'title'   => 'Alignment',
            'items' => array(
                array(
                    'title'   => 'Left',
                    'format'  => 'alignleft',
                    'icon'    => 'alignleft',
                ),
                array(
                    'title'   => 'Center',
                    'format'  => 'aligncenter',
                    'icon'    => 'aligncenter',
                ),
                array(
                    'title'   => 'Right',
                    'format'  => 'alignright',
                    'icon'    => 'alignright',
                ),
				/*
                array(
                    'title'   => 'Justify',
                    'format'  => 'alignjustify',
                    'icon'    => 'alignjustify',
                ),
				*/
            ),
        ),
    );

	// Add custom styles
	$new_styles = array(
		/*
		array(
			'title'	=> __( 'Buttons', 'scaffolding' ), // This is the title of the dropdown
			'items'	=> array(
				array(
					'title'    => __( 'Orange Button', 'scaffolding' ),
					'selector' => 'a',
					'classes'  => 'orange-btn',
					'exact'    => true
				),
			),
		),
		*/
	);

	// Merge styles
	$new_settings = array_merge( $default_styles, $new_styles );

	// Add styles in tinymce config as json data
	$settings['style_formats'] = json_encode( $new_settings );

	// Return new settings
	return $settings;

}
add_filter( 'tiny_mce_before_init', 'scaffolding_tinymce_modify_styleselect' );

/**
 * TinyMCE: Modify text colors
 * Update this to include the theme's color palette and remove the defaults
 */
function scaffolding_tinymce_modify_text_colors( $init ) {
	$default_colors = '
		"000000", "Black",
		"993300", "Burnt orange",
		"333300", "Dark olive",
		"003300", "Dark green",
		"003366", "Dark azure",
		"000080", "Navy Blue",
		"333399", "Indigo",
		"333333", "Very dark gray",
		"800000", "Maroon",
		"FF6600", "Orange",
		"808000", "Olive",
		"008000", "Green",
		"008080", "Teal",
		"0000FF", "Blue",
		"666699", "Grayish blue",
		"808080", "Gray",
		"FF0000", "Red",
		"FF9900", "Amber",
		"99CC00", "Yellow green",
		"339966", "Sea green",
		"33CCCC", "Turquoise",
		"3366FF", "Royal blue",
		"800080", "Purple",
		"999999", "Medium gray",
		"FF00FF", "Magenta",
		"FFCC00", "Gold",
		"FFFF00", "Yellow",
		"00FF00", "Lime",
		"00FFFF", "Aqua",
		"00CCFF", "Sky blue",
		"993366", "Brown",
		"C0C0C0", "Silver",
		"FF99CC", "Pink",
		"FFCC99", "Peach",
		"FFFF99", "Light yellow",
		"CCFFCC", "Pale green",
		"CCFFFF", "Pale cyan",
		"99CCFF", "Light sky blue",
		"CC99FF", "Plum",
		"FFFFFF", "White"
	';
	$custom_colors = '';
	$init['textcolor_map'] = '['.$default_colors.','.$custom_colors.']';
	$init['textcolor_rows'] = 6; // expand colour grid to 6 rows
	return $init;
}
