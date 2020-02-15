<?php
add_action( 'wp_enqueue_scripts', 'wpccp_chapter2_enqueue_parent_styles' );
function wpccp_chapter2_enqueue_parent_styles() {
 wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
 wp_enqueue_style( 'child-style',
 get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' )
 );
}


add_theme_support( 'custom-header', array(
 'video' => true,
) );


function wpccp_chapter2_conditional_content($content) {
 if( is_single() ){
   $content .= "<p>Additional Content for single post </p>";
 }else if( is_archive() ){
   $content .= "<p>Archive Page Content for each post </p>";
 }
 return $content;
}
add_filter('the_content', 'wpccp_chapter2_conditional_content');


function wpccp_chapter2_conditional_user_content($content) {
  if( is_user_logged_in() ){
    $content .= "<p>Additional Content for members </p>";
  }
  return $content;
}
add_filter('the_content', 'wpccp_chapter2_conditional_user_content');

function wpccp_chapter2_validate_page_restrictions(){
  global $wp_query;
  if (! isset($wp_query->post->ID) ) {
    return;
  }

  if(is_page('2') && ! is_user_logged_in() ){
    $url = site_url();
    wp_redirect($url);
    exit;
  }
}
add_action('template_redirect', 'wpccp_chapter2_validate_page_restrictions');




?>