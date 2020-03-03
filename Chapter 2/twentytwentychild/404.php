<?php
/**
 * The template for displaying the 404 template in the Twenty Twenty theme.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

get_header();
?>

<main id="site-content" role="main">

	<div class="section-inner thin error404-content">

		<h1 class="entry-title"><?php _e( 'Page Not Found', 'twentytwenty' ); ?></h1>

		<div class="intro-text"><p><?php _e( 'The page you were looking for could not be found. It might have been removed, renamed, or did not exist in the first place.Maybe try a search or view our posts?', 'twentytwenty' ); ?></p></div>

		<?php
		get_search_form(
			array(
				'label' => __( '404 not found', 'twentytwenty' ),
			)
		);
		?>

		<?php 
			 $post_list = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=> 3 )); ?>

			<?php if ( $post_list->have_posts() ) : ?>
			 <div id="list-post-panel">
			 <ul>
			 <?php while ( $post_list->have_posts() ) : $post_list->the_post();
			 $image = get_the_post_thumbnail_url( get_the_ID()); ?>
			 <li>
			 <div class="post-list-featured-image"><img src="<?php echo $image; ?>" /></div>
			 <div class="post-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
			 </li>
			 <?php endwhile; ?>
			 </ul>
			 <?php wp_reset_postdata(); ?>
			<?php endif; ?>
			</div>

	</div><!-- .section-inner -->

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php
get_footer();
