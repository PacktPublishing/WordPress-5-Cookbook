<?php
/**
 * Plugin Name: WPCookbook Chapter 8
 * Plugin URI: 
 * Description: Chapter 8 code implementations.
 * Version: 1.0
 * Author: Rakhitha Nimesh
 * Author URI: 
 * Author Email: 
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpccp
 * Domain Path: /languages
 */

function wpccp_ch8_custom_search($query) {
    if ($query->is_search && !is_admin() ) {
		if(is_user_logged_in()){ 
		    $query->set('post_type',array('book','post','page'));
		}else{
		    $query->set('post_type',array('post'));
		}

		// Searching specific category and meta value
		$query->set( 'cat', '55' );  8
        $query->set( 'meta_key', 'wpccp_post_allowed_user' );
        $query->set( 'meta_value', '8' ); 

        // Limit serahcing to set of posts
        $query->set ( 'post__in', array('1','4','6') ); 

        // Differntiate search results for multiple searches
        if(isset($_GET['search_type'])) {
            $type = $_GET['search_type'];
            if($type == 'book') {
                $query->set('post_type',array('book'));
                $query->set( 'cat', '-4' );
            }
        }       
    }


	return $query;
}
add_filter('pre_get_posts','wpccp_ch8_custom_search');