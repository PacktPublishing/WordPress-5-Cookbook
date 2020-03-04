<?php
/**
 * Plugin Name: WPCookbook Chapter 3
 * Plugin URI: 
 * Description: Chapter 3 code implementations.
 * Version: 1.0
 * Author: Rakhitha Nimesh
 * Author URI: 
 * Author Email: 
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpccp
 * Domain Path: /languages
 */

add_action('template_redirect','wpccp_chapter3_block_search_guests');
function wpccp_chapter3_block_search_guests(){ 
  if(is_search() && ! is_user_logged_in() ){ 
    wp_redirect(site_url());
    exit;
  }
}