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
        $author_id = $post->post_author;
        $user_info = get_userdata($author_id);

        $author_name = get_user_meta($author_id, 'first_name', true) . " " . get_user_meta($author_id, 'last_name', true);
        $description = get_user_meta($author_id, 'description', true);
        $author_website = $user_info->user_url;
        $avatar = get_avatar( $author_id , 32 );

        $content .= "<div id='wpccp_author_profile'>
                        <div id='wpccp_author_profile_image'>
                            ".$avatar."
                        </div>
                        <div id='wpccp_author_profile_name'>
                            ".$author_name."
                        </div>
                        <div id='wpccp_author_profile_website'>
                            <a href='".$author_website."'>".__('Website','wpccp_ch6')."</a>
                        </div>
                        <div id='wpccp_author_profile_description'>
                            ".$description."
                        </div>
                    </div>";
    }
  
    return $content;
}
add_filter('the_content', 'wpccp_chapter6_author_post_profile');

function wpccp_chapter6_register_plugin_styles() {
    wp_register_style( 'wpccp-ch6', plugin_dir_url( __FILE__ ).'css/style.css'  );
    wp_enqueue_style( 'wpccp-ch6' );
}
add_action( 'wp_enqueue_scripts', 'wpccp_chapter6_register_plugin_styles' );


add_filter('manage_users_columns', 'wpccp_chapter6_user_custom_columns');
function wpccp_chapter6_user_custom_columns( $column ) {
    $column['wpccp_author_info'] = __('Author Info','wpccp_ch6');
    return $column;
}

add_action('manage_users_custom_column', 'wpccp_chapter6_user_custom_column_values', 10, 3);
function wpccp_chapter6_user_custom_column_values( $val, $column_name, $user_id ) {
    $info_url = site_url(). "?view_author_profile=yes&author=".$user_id;
    
         
    switch ($column_name) {
        case 'wpccp_author_info' :
            return "<a href='#' onClick=window.open('".$info_url."','pagename','resizable,top=200,left=300,height=260,width=570'); >". __('View Author Info','wpccp_ch6') ."</a>";
            break; 
        default:
            return $val;
            break;
    }
}


add_action('init', 'wpccp_chapter6_display_author_data', 10, 3);
function wpccp_chapter6_display_author_data() {
    global $wpdb;

    if(isset($_GET['view_author_profile'])){
        $author_id = isset($_GET['author']) ? (int) $_GET['author'] : 0;
        $user_info = get_userdata($author_id);

        $query = "SELECT count($wpdb->posts.ID) as total, $wpdb->posts.post_status
                    FROM $wpdb->posts
                    WHERE $wpdb->posts.post_author = ".$author_id ." 
                    AND $wpdb->posts.post_type = 'post'
                    GROUP BY  $wpdb->posts.post_status";

        $author_posts = $wpdb->get_results($query, OBJECT);

        $assigned_posts = 0;
        $published_posts = 0;
        $pending_review_posts = 0;
        
        foreach ($author_posts as $key => $author_post_record) {
            switch ($author_post_record->post_status) {
                case 'publish':
                    $published_posts = $author_post_record->total;
                    break;
                case 'pending':
                    $pending_review_posts = $author_post_record->total;
                    break;
                case 'assigned':
                    $assigned_posts = $author_post_record->total;
                    break;
            }
        }        

        $display = "<h2>".$user_info->user_login."</h2>";
        $display .= "<table>
                        <tr><td>".__('Assigned Posts','wpccp_ch6')."</td><td>".$assigned_posts."</td></tr>
                        <tr><td>".__('Pending Posts','wpccp_ch6')."</td><td>".$pending_review_posts."</td></tr>
                        <tr><td>".__('Published Posts','wpccp_ch6')."</td><td>".$published_posts."</td></tr>
                    </table>";
        echo $display;exit;
    }
}

add_action( 'add_meta_boxes', 'wpccp_chapter6_user_meta_box');
function wpccp_chapter6_user_meta_box(){
    if(current_user_can('manage_options')){
        
        add_meta_box(
                    'wpccp-chapter4-user-meta-box',
                    __( 'User Permissions', 'wpccp_ch6' ),
                    'wpccp_chapter6_add_post_user',
                    'post',
                    'normal',
                    'high'
                );
    }
}

function wpccp_chapter6_add_post_user(){
    
    $display .= "<span>".__('Post is visible to :','wpccp_ch6')."</span><select name='wpccp_post_allowed_user' >
                    <option value='0' >".__('Select User','wpccp_ch6')."</option>";

    $users_query = new WP_User_Query( array ( 'orderby' => 'post_count', 'order' => 'DESC' ) );

        // If we found some users, loop through and display them
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
        $visibility_user = (int) get_post_meta( $post_id, 'wpccp_post_allowed_user', true );
        if( $visibility_user != '0' && $current_user_id != $visibility_user ){
            wp_redirect('http://localhost/cookbook1');exit;
        }
    }   
    if(is_archive() || is_feed() || is_search() || is_home() ){        
        if(isset($wp_query->posts) && is_array($wp_query->posts)){
            foreach ($wp_query->posts as $key => $post_obj) {
                $visibility_user = get_post_meta( $post_obj->ID , 'wpccp_post_allowed_user', true );
                if( $visibility_user != '' && $current_user_id != $visibility_user ){
                    $wp_query->posts[$key]->post_title = "RESTRICTED";
                    $wp_query->posts[$key]->post_content = "";
                }                
            }
        }
    }
    return;
}

