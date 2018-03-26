<?php
/**
 * The template for displaying search forms
 *
 * @package Scaffolding
 */
?>

	<form method="get" id="searchform" class="clearfix" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label class="screen-reader-text" for="s"><?php _e( 'Search for:', 'scaffolding' ); ?></label>
		<input type="text" name="s" id="s" value="<?php esc_attr_e( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search the Site&hellip;', 'scaffolding' ); ?>" />
		<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Go', 'scaffolding' ); ?>" />
	</form>