<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<div class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentynineteen' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search or view our posts?', 'twentynineteen' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .page-content -->

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
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
