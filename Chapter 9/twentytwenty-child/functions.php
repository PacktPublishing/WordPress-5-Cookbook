<?php

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() { 
   wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
   wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' )
    );
}

add_action('woocommerce_before_shop_loop','wpccp_woocommerce_before_shop_loop',5);
function wpccp_woocommerce_before_shop_loop(){

    $html = '<h2 class="wpccp_featured_products_title">Featured Products</h2>
    <div class="wpccp_featured_products">'. do_shortcode("[featured_products limit='8' columns='4' ]").'</div>';

    $shop_page_id = get_option( 'woocommerce_shop_page_id' );
    $thumbnail = get_the_post_thumbnail($shop_page_id);
    $thumbnail_id = get_post_thumbnail_id($shop_page_id);
    $link = get_permalink($thumbnail_id);
    $html .= '<div class="featured-image"><a href="' . $link . '">'. $thumbnail
            . '</a></div>';

    echo $html;
}

add_action( 'woocommerce_single_product_summary', 'wpccp_woocommerce_custom_data', 25 );
function wpccp_woocommerce_custom_data(){
	echo "Free E-Book Available for this Purchase";
}

add_action( 'woocommerce_single_product_summary', 'wpccp_woocommerce_single_product_summary_data', 70 );
function wpccp_woocommerce_single_product_summary_data(){
	echo "This content shows after Add to Cart button";
}