<?php
/**
 * Plugin Name: WPCookbook Chapter 6
 * Plugin URI: 
 * Description: Chapter 6 code implementations.
 * Version: 1.0
 * Author: Rakhitha Nimesh
 * Author URI: 
 * Author Email: 
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpccp
 * Domain Path: /languages
 */

function wpccp_chapter6_author_post_profile($content) {
 global $post;

 if(isset($post->post_author) ){
   // Step 3 code should be placed in next line
    $author_id = $post->post_author;
    $user_info = get_userdata($author_id);

    $author_name = get_user_meta($author_id, 'first_name', true) . " " . get_user_meta($author_id, 'last_name', true);
    $description = get_user_meta($author_id, 'description', true);
    $author_website = $user_info->user_url;
    $avatar = get_avatar( $author_id , 32 );

    $content .= "<div id='wpccp_author_profile'>
     <div id='wpccp_author_profile_image'>".$avatar."
     </div>
     <div id='wpccp_author_profile_name'>".$author_name."
     </div>
     <div id='wpccp_author_profile_website'><a href='".$author_website."'>".__('Website','wpccp_ch6')."</a>
     </div>
     <div id='wpccp_author_profile_description'>".$description."
     </div>
     </div>";

 } 
 return $content;
}
add_filter('the_content', 'wpccp_chapter6_author_post_profile');


function wpccp_chapter6_register_plugin_styles() {
  wp_register_style( 'wpccp-ch6', plugin_dir_url( __FILE__ ).'css/style.css' );
  wp_enqueue_style( 'wpccp-ch6' );
}
add_action( 'wp_enqueue_scripts', 'wpccp_chapter6_register_plugin_styles' );

add_action( 'add_meta_boxes', 'wpccp_chapter6_user_meta_box');
function wpccp_chapter6_user_meta_box(){
     if(current_user_can('manage_options')){ 
     add_meta_box(
     'wpccp-chapter4-user-meta-box',
     __( 'User Permissions', 'wpccp_ch6' ),
     'wpccp_chapter6_add_post_user',
     'post', 'normal', 'high'
     );
     }
}

function wpccp_chapter6_add_post_user(){ 
     $display .= "<span>".__('Post is visible to :','wpccp_ch6')."</span><select name='wpccp_post_allowed_user' >
     <option value='0' >".__('Select User','wpccp_ch6')."</option>";

     $users_query = new WP_User_Query( array ( 'orderby' => 'post_count', 'order' => 'DESC' ) );

     if ( ! empty( $users_query->results ) ) {
     foreach ( (array) $users_query->results as $user ) {
     $display .= "<option value='".$user->ID."'>".$user->user_nicename."</option>"; 
     }
     }

     $display .= "</select>";
     $display .= '<input type="hidden" name="wpccp_backend_group_add_new_member_nonce" value="'.wp_create_nonce( 'wpccp-backend-group-add-new-member-nonce' ).' " />';

     echo $display;
}

add_action( 'save_post', 'wpccp_chapter6_save_post_restrictions' );
function wpccp_chapter6_save_post_restrictions($post_id){
     if ( isset( $_POST['wppcp_restriction_settings_nonce'] ) && ! wp_verify_nonce( $_POST['wppcp_restriction_settings_nonce'], 'wppcp_restriction_settings' ) ) {
     return;
     }

     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
     return;
     }

     if ( ! current_user_can( 'manage_options', $post_id ) ) {
     return;
     }

     $visibility_user = isset( $_POST['wpccp_post_allowed_user'] ) ? sanitize_text_field($_POST['wpccp_post_allowed_user']) : 'none'; 
     update_post_meta( $post_id, 'wpccp_post_allowed_user', $visibility_user ); 
}

add_action('template_redirect', 'wpccp_chapter6_validate_post_restrictions', 1); 
function wpccp_chapter6_validate_post_restrictions(){
      global $wp_query;
      $current_user_id = get_current_user_id();

      if (! isset($wp_query->post->ID) ) {
        return;
      }

      if( is_single() ){
        $post_id = $wp_query->post->ID;
        $visibility_user = get_post_meta( $post_id, 'wpccp_post_allowed_user', true );
        if( ( $visibility_user != '' && $visibility_user != '' )&& $current_user_id != $visibility_user ){
          wp_redirect(site_url());exit;
        }
      } 

      if(is_archive() || is_feed() || is_search() || is_home() ){ 
        if(isset($wp_query->posts) && is_array($wp_query->posts)){
          foreach ($wp_query->posts as $key => $post_obj) {
            $visibility_user = get_post_meta( $post_obj->ID , 'wpccp_post_allowed_user', true );

            if( ( $visibility_user != '' && $visibility_user != 'none' ) && $current_user_id != $visibility_user ){
              $wp_query->posts[$key]->post_title = "RESTRICTED";
              $wp_query->posts[$key]->post_content = "";
            } 
          }
        }
      }
      return;
}