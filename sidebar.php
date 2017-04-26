<?php
/**
 * Sidebar Template
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Scaffolding
 * @since Scaffolding 1.0
 */
 
// Get sidebar classes based on active sidebars
$sidebar_class = scaffolding_set_layout_classes( 'sidebar' );
?>

<?php if ( is_active_sidebar( 'left-sidebar' ) ) : ?>

	<div id="left-sidebar" class="sidebar <?php echo $sidebar_class['left']; ?> clearfix" role="complementary">
		<?php dynamic_sidebar( 'left-sidebar' ); ?>
	</div>

<?php endif; ?>

<?php if ( is_active_sidebar( 'right-sidebar' ) ) : ?>

	<div id="right-sidebar" class="sidebar <?php echo $sidebar_class['right']; ?> clearfix" role="complementary">
		<?php dynamic_sidebar( 'right-sidebar' ); ?>
	</div>

<?php endif;
