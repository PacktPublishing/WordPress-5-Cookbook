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
  }
  return $query;
}
add_filter('pre_get_posts','wpccp_ch8_custom_search');