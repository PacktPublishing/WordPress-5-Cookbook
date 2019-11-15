<?php
/**
 * Plugin Name: WPCookbook Chapter 7
 * Plugin URI: 
 * Description: Chapter 7 code implementations.
 * Version: 1.0
 * Author: Rakhitha Nimesh
 * Author URI: 
 * Author Email: 
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpccp
 * Domain Path: /languages
 */

// Register Custom Post Type
function wpccp_chapter7_book_post_type() {

    $labels = array(
        'name'                  => _x( 'Books', 'Post Type General Name', 'wpccp_ch7' ),
        'singular_name'         => _x( 'Book', 'Post Type Singular Name', 'wpccp_ch7' ),
        'menu_name'             => __( 'Books', 'wpccp_ch7' ),
        'name_admin_bar'        => __( 'Book', 'wpccp_ch7' ),
        'archives'              => __( 'Book Archives', 'wpccp_ch7' ),
        'attributes'            => __( 'Book Attributes', 'wpccp_ch7' ),
        'parent_item_colon'     => __( 'Parent Book:', 'wpccp_ch7' ),
        'all_items'             => __( 'All Books', 'wpccp_ch7' ),
        'add_new_item'          => __( 'Add New Book', 'wpccp_ch7' ),
        'add_new'               => __( 'Add New', 'wpccp_ch7' ),
        'new_item'              => __( 'New Book', 'wpccp_ch7' ),
        'edit_item'             => __( 'Edit Book', 'wpccp_ch7' ),
        'update_item'           => __( 'Update Book', 'wpccp_ch7' ),
        'view_item'             => __( 'View Book', 'wpccp_ch7' ),
        'view_items'            => __( 'View Books', 'wpccp_ch7' ),
        'search_items'          => __( 'Search Book', 'wpccp_ch7' ),
        'not_found'             => __( 'Not found', 'wpccp_ch7' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'wpccp_ch7' ),
        'featured_image'        => __( 'Featured Image', 'wpccp_ch7' ),
        'set_featured_image'    => __( 'Set featured image', 'wpccp_ch7' ),
        'remove_featured_image' => __( 'Remove featured image', 'wpccp_ch7' ),
        'use_featured_image'    => __( 'Use as featured image', 'wpccp_ch7' ),
        'insert_into_item'      => __( 'Insert into book', 'wpccp_ch7' ),
        'uploaded_to_this_item' => __( 'Uploaded to this book', 'wpccp_ch7' ),
        'items_list'            => __( 'Books list', 'wpccp_ch7' ),
        'items_list_navigation' => __( 'Books list navigation', 'wpccp_ch7' ),
        'filter_items_list'     => __( 'Filter book list', 'wpccp_ch7' ),
    );
    $args = array(
        'label'                 => __( 'Book', 'wpccp_ch7' ),
        'description'           => __( 'Book Description', 'wpccp_ch7' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'public'                => true,  
        'has_archive'           => true,
        'hierarchical' => false,
        //'show_in_rest' => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'book', $args );

}
add_action( 'init', 'wpccp_chapter7_book_post_type');

// add_action( 'add_meta_boxes', 'wpccp_chapter7_book_meta_box');
// function wpccp_chapter7_book_meta_box(){
//     if(current_user_can('manage_options')){        
//         add_meta_box(
//                     'wpccp-chapter7-book-meta-box',
//                     __( 'Book Custom Fields', 'wpccp_ch7' ),
//                     'wpccp_chapter7_display_book_fields',
//                     'book',
//                     'normal',
//                     'high'
//                 );
//     }
// }

// function wpccp_chapter7_display_book_fields($post){
//     global $wpdb;
//     // $wpccp_book_price = get_post_meta( $post->ID, 'wpccp_book_price', true ); 
//     // $wpccp_book_pages =  get_post_meta( $post->ID, 'wpccp_book_pages', true );

//     $sql  = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpccp_book_details WHERE book_id = %d  ", $post->ID );
//     $result = $wpdb->get_results($sql);
//     if(isset($result[0])){
//         $wpccp_book_price = $result[0]->book_price; 
//         $wpccp_book_pages = $result[0]->book_pages;
//     }

//     $display .= "<p><span>".__('Book Pages :','wpccp_ch7')."</span><input type='text' name='wpccp_book_pages'  value='".$wpccp_book_pages."' /></p>";
//     $display .= "<p><span>".__('Book Price :','wpccp_ch7')."</span><input type='text' name='wpccp_book_price' value='".$wpccp_book_price."' /></p>";
    
//     $display .= wp_nonce_field('wpccp_backend_book_nonce','wpccp_backend_book_nonce');
//         echo $display;
// }

// add_action( 'save_post', 'wpccp_chapter7_save_book_data',10,3 );
// function wpccp_chapter7_save_book_data($post_id, $post, $update){
//     global $post,$wpdb;

//     if(isset($post->post_type) && $post->post_type != 'book'){
//         return;
//     }

//     if ( isset( $_POST['wpccp_backend_book_nonce'] ) && ! wp_verify_nonce( $_POST['wpccp_backend_book_nonce'], 'wpccp_backend_book_nonce' ) ) {
//         return;
//     }

//     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
//         return;
//     }

//     if ( ! current_user_can( 'manage_options', $post_id ) ) {
//         return;
//     }

//     $wpccp_book_price = isset( $_POST['wpccp_book_price'] ) ? sanitize_text_field($_POST['wpccp_book_price']) : '';
//     $wpccp_book_pages = isset( $_POST['wpccp_book_pages'] ) ? (int) ($_POST['wpccp_book_pages']) : '';


//     $sql  = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpccp_book_details WHERE book_id = %d  ", $post->ID );
//     $result = $wpdb->get_results($sql);
//     if(isset($result[0])){

//         $wpdb->update( 
//             "{$wpdb->prefix}wpccp_book_details", 
//             array( 
//                 'book_id'  => $post_id, 
//                 'book_price' => $wpccp_book_price,
//                 'book_pages' => $wpccp_book_pages,
//                 'updated_at' => date("Y-m-d H:i:s")
//             ), 
//             array( 'book_id' => $post_id ),
//             array( 
//                 '%d', 
//                 '%s',
//                 '%d',
//                 '%s'
//             ),
//             array( '%d' ) );
//     }else{
//         $wpdb->insert( 
//             "{$wpdb->prefix}wpccp_book_details", 
//             array( 
//                 'book_id'  => $post_id, 
//                 'book_price' => $wpccp_book_price,
//                 'book_pages' => $wpccp_book_pages,
//                 'updated_at' => date("Y-m-d H:i:s")
//             ), 
//             array( 
//                 '%d', 
//                 '%s',
//                 '%d',
//                 '%s'
//             ) );
//     }
  
    
//     // update_post_meta( $post_id, 'wpccp_book_price', $wpccp_book_price ); 
//     // update_post_meta( $post_id, 'wpccp_book_pages', $wpccp_book_pages );   
// }




// add_filter('template_include', 'wpccp_chapter7_book_list_template');
// function wpccp_chapter7_book_list_template( $template ) {
//   if ( is_post_type_archive('book') ) {
//       return plugin_dir_path(__FILE__) . 'archive_books.php';
//   }
//   return $template;
// }

// add_shortcode('wpccp_chapter7_books_by_author','wpccp_chapter7_books_by_author');
// function wpccp_chapter7_books_by_author(){

//     $html = '';
//     $query = new WP_Query( array( 'author' => 1, 'post_type' => 'book' , 'post_status' => 'publish' ) );
//     if ( $query->have_posts() ) {
//             while ( $query->have_posts() ) : $query->the_post(); 
//                     $html .= '<h2 ><a href="'.get_permalink().'">'. get_the_title() .'</a></h2>';
//             endwhile;
//             wp_reset_postdata();
//     }

//     return $html;
// }


// add_shortcode('wpccp_chapter7_book_field','wpccp_chapter7_book_field');
// function wpccp_chapter7_book_field($atts){
//     $field_value = '';
//     if(isset($atts['book_id']) && $atts['book_id'] != 0){
//         $field_value = get_post_meta($atts['book_id'],$atts['field_key'],true);
//     }
    
//     return $field_value;
// }


// add_shortcode('wpccp_chapter7_restrict_access','wpccp_chapter7_restrict_access');
// function wpccp_chapter7_restrict_access($atts,$content){

//     $atts = shortcode_atts(
//         array(
//             'type' => 'all',
//             'role' => '',
//         ), $atts
//     );

//     if(isset($atts['type'])){
//         switch ($atts['type']) {
//             case 'all':
//                 break;

//             case 'member':
//                 if(!is_user_logged_in()){
//                     $content = 'RESTRICTED';
//                 }
//                 break;

//             case 'role':
//                 $role = $atts['role'];
//                 if(! current_user_can($role) ){
//                     $content = 'RESTRICTED';
//                 }
//                 break;
   
//             default:
//                 # code...
//                 break;
//         }
//     }
    
//     return $content;
// }






// add_action('init','wpccp_chapter7_manage_user_routes');
// function wpccp_chapter7_manage_user_routes(){
//     add_rewrite_rule( '^user-profile/([^/]+)/?', 'index.php?wpcpp_user_id=$matches[1]', 'top' );
// }

// add_filter( 'query_vars', 'wpccp_chapter7_manage_user_routes_query_vars'  );
// function wpccp_chapter7_manage_user_routes_query_vars( $query_vars ) {
//     $query_vars[] = 'wpcpp_user_id';
//     return $query_vars;
// }

// add_action( 'template_include', 'wpccp_chapter7_user_controller'  );
// function wpccp_chapter7_user_controller( $template ) {
//     global $wp_query,$wpcpp_user_id;
//     $wpcpp_user_id = isset ( $wp_query->query_vars['wpcpp_user_id'] ) ? $wp_query->query_vars['wpcpp_user_id'] : '';

//     if($wpcpp_user_id != ''){
//         $template = plugin_dir_path(__FILE__) . 'user_profile.php';
//     }

//     return $template;
// }





// add_action('init','wpccp_chapter7_access_database');
// function wpccp_chapter7_access_database(){
//     $action  = isset($_GET['wpccp_test']) ? sanitize_text_field($_GET['wpccp_test']) : '';
//     switch ($action) {
//         case 'insert_post':
//             $post_data = array(
//                 'post_title' => 'Frontend Post 1',
//                 'post_content' => 'Frontend Post 1 Content',
//                 'post_type' => 'post',
//                 'post_status' => 'publish',
//             );
//             $post_id = wp_insert_post( $post_data );
//             break;
        
//         case 'update_post':
//             $post_data = array(
//               'ID'           => 5,
//               'post_content' => 'Frontend Post 1 Updated Content',
//             );
         
//             wp_update_post( $post_data );
//             break;

//         case 'delete_post':
//             wp_delete_post( 5 , true); // Set to False if you want to send them to Trash.
//             break;

//         case 'insert_user':
//             $user_id = wp_create_user( 'test_user' , 'test_user_password', 'test@example.com' );
//             break;

//         case 'update_user':
//             $user_data = wp_update_user( array( 'ID' => 6 , 'user_url' => 'http://www.mysite.com' ) );
//             break;

//         case 'delete_user':
//             require_once(ABSPATH.'wp-admin/includes/user.php');
//             wp_delete_user( 6 ); 
//             break;
//     }
// }


// register_activation_hook( __FILE__, 'wpccp_chapter7_install_db_tables' );
// function wpccp_chapter7_install_db_tables(){
//     global $wpdb,$wp_roles;

//     $wpccp_book_details = $wpdb->prefix . 'wpccp_book_details';

//     $sql_wpccp_book_details = "CREATE TABLE IF NOT EXISTS $wpccp_book_details (
//               id int(11) NOT NULL AUTO_INCREMENT,
//               book_id int(11) NOT NULL,
//               book_price varchar(256) NOT NULL,
//               book_pages int(11) NOT NULL,
//               updated_at datetime NOT NULL,
//               PRIMARY KEY (id)
//             );";


//     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//     dbDelta( $sql_wpccp_book_details );

// }


// add_shortcode('wpccp_rest_api_post_list','wpccp_chapter7_rest_api_post_list');
// function wpccp_chapter7_rest_api_post_list(){
//     $request = wp_remote_get( 'http://localhost/cookbook1/wp-json/wp/v2/posts?_fields=author,id,excerpt,title,link' );

//     if( is_wp_error( $request ) ) {
//         return false; 
//     }

//     $body = wp_remote_retrieve_body( $request );

//     $data = json_decode( $body );
//     $html = "<table>";

//     foreach ($data as $key => $value) {
//         $html .= "<tr><td>".$value->id."</td><td><a href='".$value->link."'>".$value->title->rendered ."</a></td></tr>";
//     }
    
//     $html .= "</table>";
//     return $html;
// }