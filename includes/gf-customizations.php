<?php
/**
 * Gravity Forms Customizations
 *
 * Include any customizations here.
 *
 * @package Scaffolding
 */

/**
 * Add the ability to hide labels
 *
 * @link https://gravitywiz.com/how-to-hide-gravity-form-field-labels-when-using-placeholders/
 */
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );