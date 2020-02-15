<?php /* Template Name: Product Landing Page */ ?>


<?php get_header(); ?>
  <section id="primary" class="content-area">
  <h2>Product Page</h2>
  <main id="main" class="site-main">
    <?php
      while ( have_posts() ) :
        the_post();
        get_template_part( 'template-parts/content/content', 'page' );
      endwhile;
    ?>
  </main><!-- #main -->
  </section><!-- #primary -->
<?php get_footer();