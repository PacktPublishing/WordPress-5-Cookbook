<?php
get_header();
?>
	<section id="primary" class="content-area">
		<main id="main" class="site-main">
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<?php echo __('Book List','wpccp_ch7'); ?>
			</header>
			
			<?php
			while ( have_posts() ) : the_post();
				?>
				<div style="margin:30px auto;width:80%;background: #eee;padding: 10px"> 
					<h2 ><?php the_title(); ?></h2>
					<div><?php the_content(); ?></div>
					<div>Price : <?php echo get_post_meta(get_the_ID(), 'wpccp_book_price' , true); ?></div>
					<div>Pages : <?php echo get_post_meta(get_the_ID(), 'wpccp_book_pages' , true); ?></div>
				</div>
				<?php
			endwhile;
			twentynineteen_the_posts_navigation();
		else :
			get_template_part( 'template-parts/content/content', 'none' );
		endif;
		?>
		</main><!-- #main -->
	</section><!-- #primary -->
<?php
get_footer();
