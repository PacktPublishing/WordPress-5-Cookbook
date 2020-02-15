<?php /* Template Name: Post Grid */ ?>

<?php
  get_header();
  $post_list = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>-1)); 
?>

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
<?php else : ?>
  <p><?php _e( 'There no posts to display.' ); ?></p>
<?php endif; ?>
</div>
<?php get_footer(); ?>