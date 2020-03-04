<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

get_header();
?>

<main id="site-content" role="main">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		}
	}

	if ( is_active_sidebar( 'after_post_content_1' ) ) : ?>
		 <div  style="max-width: 80%;margin: 0 10%;padding: 0 60px;"  id="primary-post-content-sidebar" class="primary-post-content-sidebar widget-area" role="complementary">
		 <?php dynamic_sidebar( 'after_post_content_1' ); ?>
		 </div><!-- #primary-sidebar -->
		<?php endif; 

	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
