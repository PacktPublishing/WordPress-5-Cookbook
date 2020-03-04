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


class WP_Widget_Product_Pages extends WP_Widget {
 public function __construct() {
 $widget_ops = array(
 'classname' => 'widget_product_pages',
 'description' => __( 'Pages with Product Landing Page Template.' ),
 );
 parent::__construct( 'product-pages', __( 'Product Pages' ), $widget_ops );
 }
 public function form( $instance ) {
 // Code in Step 3 should be added in the next line
    $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
    $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
    ?>

    <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

    <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
    <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

    <?php
 }


 public function widget( $args, $instance ) { 
 // Code in Step 4 should be added in the next line
    $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Product Pages' );
    $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;

    $query = new WP_Query(
     array(
     'posts_per_page' => $number,
     'post_status' => 'publish',
     'post_type' => 'page',
     'meta_key' => '_wp_page_template',
     'meta_value' => 'product.php'
     ) );
    if ( ! $query->have_posts() ) {
     return;
    }
    ?>

    <?php echo $args['before_widget']; ?>
    <?php
    if ( $title ) {
     echo $args['before_title'] . $title . $args['after_title'];
    }
    ?>

    <ul>
     <?php foreach ( $query->posts as $product_page ) : ?>
     <?php
     $post_title = get_the_title( $product_page->ID );
     $title = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
     ?>
     <li>
     <a href="<?php the_permalink( $product_page->ID ); ?>"><?php echo $title; ?></a>
     </li>
     <?php endforeach; ?>
    </ul>

    <?php
    echo $args['after_widget'];

 }
 public function update( $new_instance, $old_instance ) { 
 // Code in Step 5 should be added in the next line
    $instance = $old_instance;
    $instance['title'] = sanitize_text_field( $new_instance['title'] );
    $instance['number'] = (int) $new_instance['number'];
    return $instance;
 } 
}

function wpccp_chapter3_register_widgets() {
  register_widget( 'WP_Widget_Product_Pages' );
}
add_action( 'widgets_init', 'wpccp_chapter3_register_widgets' );

function wpccp_chapter3_widgets_init() {
 register_sidebar( array(
 'name' => __('After Post Content','wpccp_ch3'),
 'id' => 'after_post_content_1',
 'before_widget' => '<div class="widget-column">',
 'after_widget' => '</div>',
 'before_title' => '<h2 class="rounded">',
 'after_title' => '</h2>',
 ) );
}
add_action( 'widgets_init', 'wpccp_chapter3_widgets_init' );


add_filter( 'widget_display_callback', 'wpccp_chapter3_content_visibility' ,10,3 );
function wpccp_chapter3_content_visibility($instance, $current_obj, $args){ 
 if($current_obj->id == 'text-4'){
     if(is_page()){
        return $instance; 
     }else{
        return false;
     }
 }
 
 if($current_obj->id == 'text-5'){
     if(is_user_logged_in()){
        return $instance; 
     }else{
        return false;
     }
 }
}

?>