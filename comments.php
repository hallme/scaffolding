<?php
/**
 * Comments Template
 *
 * The area of the page that contains both current comments and the comment form.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Scaffolding
 */

// Do not delete these lines
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'Please do not load this page directly. Thanks!' );
}

/**
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) { ?>
	<div class="alert help">
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'scaffolding' ); ?></p>
	</div>
	<?php
	return;
}
?>

<?php // You can start editing here -- including this comment!
if ( have_comments() ) : ?>

	<div id="comments" class="comments-area">

		<h3 class="h2 comments-title"><?php comments_number( __( '<span>No</span> Responses', 'scaffolding' ), __( '<span>One</span> Response', 'scaffolding' ), _n( '<span>%</span> Response', '<span>%</span> Responses', get_comments_number(), 'scaffolding' ) ); ?> to &#8220;<?php the_title(); ?>&#8221;</h3>
		
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav role="navigation" id="comment-nav-above" class="site-navigation comment-navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'scaffolding' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'scaffolding' ) ); ?></div>
			</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php 
				wp_list_comments( array(
					'type' 		=> 'comment',
					'style'		=> 'ol',
					'callback'	=> scaffolding_comments,
				) ); 
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'scaffolding' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'scaffolding' ) ); ?></div>
			</nav>
		<?php endif; // check for comment navigation ?>

	</div>

<?php // this is displayed if there are no comments so far
else :
?>

	<?php if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="nocomments"><?php _e( 'Comments are closed.', 'scaffolding' ); ?></p>

	<?php endif; ?>

<?php
endif;
?>

<?php  // if you delete this the sky will fall on your head
if ( comments_open() ) : ?>

	<section class="respond-form">

		<?php // If registration required and not logged in
		if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) : ?>

			<div class="alert help">
				<p><?php printf( __( 'You must be %1$slogged in%2$s to post a comment.', 'scaffolding' ), '<a href="<?php echo wp_login_url( get_permalink() ); ?>">', '</a>' ); ?></p>
			</div>

		<?php else : ?>

			<form action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post" id="commentform">
				<?php comment_form(); ?>
			</form>

		<?php endif; ?>

	</section>

<?php
endif;
