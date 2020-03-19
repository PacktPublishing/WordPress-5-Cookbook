<?php

/**
 * Plugin Name: WPCookbook Chapter 12
 * Plugin URI: 
 * Description: Chapter 12 code implementations.
 * Version: 1.0
 * Author: Rakhitha Nimesh
 * Author URI: 
 * Author Email: 
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpccp
 * Domain Path: /languages
 */


add_action('admin_init', 'disable_plugin_editor_wp');
function disable_plugin_editor_wp(){
  $user_id = get_current_user_id();
  $user = get_user_by('ID', $user_id);
  if($user && $user->user_login == 'admin'){
    define( 'DISALLOW_FILE_EDIT', false );
  }else{
    define( 'DISALLOW_FILE_EDIT', true );
  }
}

function wpcpp_ch12_register_password_expire_reset($user_id){
  $expire_date = date('Y-m-d', strtotime('+1 month'));
  update_user_meta($user_id,'wpccp_password_expire_date',$expire_date);
}
add_action( 'user_register', 'wpcpp_ch12_register_password_expire_reset', 10, 1 );

add_action( 'after_password_reset', 'wpcpp_ch12_password_expire_reset', 10 , 2 );
function wpcpp_ch12_password_expire_reset($user,$new_password){
  $expire_date = date('Y-m-d', strtotime('+1 month'));
  update_user_meta($user->ID,'wpccp_password_expire_date',$expire_date);
}

function wpcpp_ch12_profile_update_password_expire_reset( $user_id ) {
  if ( ! isset( $_POST['pass1'] ) || '' == $_POST['pass1'] ) {
    return;
  }
  $expire_date = date('Y-m-d', strtotime('+1 month'));
  update_user_meta($user_id,'wpccp_password_expire_date',$expire_date);
}
add_action( 'profile_update', 'wpcpp_ch12_profile_update_password_expire_reset' );


add_filter( 'authenticate', 'wpcpp_ch12_authenticate', 30, 3 );
function wpcpp_ch12_authenticate( $user, $username, $password ) {
  if(isset($user->ID)){
    $expire_date = get_user_meta($user->ID,'wpccp_password_expire_date',true);
    if($expire_date == ''){
      $expire_date = date('Y-m-d', strtotime('+1 month'));
      update_user_meta($user->ID,'wpccp_password_expire_date',$expire_date);
    }else{
      if( date("Y-m-d") > $expire_date){
        $user = new WP_Error( 'authentication_failed', sprintf('<strong>ERROR</strong>: The password expired. You must <a href="%s">reset your password</a>.', site_url( 'wp-login.php?action=lostpassword', 'login' ) ) );
      }
    }
  }
  return $user;
}

function wpcpp_ch12_login_errors($error){
 return 'Login Failed';
}
add_filter( 'login_errors', 'wpcpp_ch12_login_errors' );

add_action('init','wpcpp_ch12_global_password_protection');
function wpcpp_ch12_global_password_protection(){

	if(isset($_COOKIE['wpcpp_ch12_password_verified'])) {
	   return; 
	 }

	 $error_message = "";
	 $global_password = 'nkuin3';
	 $user_global_password = isset($_POST['wpcpp_global_password']) ? sanitize_text_field($_POST['wpcpp_global_password']) : '';

	 if($user_global_password != ''){
	   if($global_password == $user_global_password){
	     setcookie("wpcpp_ch12_password_verified", "verified" , time()+300); 
	     return;
	   }else{
	     $error_message = __('Invalid Password','wpccp');
	   }
	 }

    $html = "<div>".$error_message."</div>
    <form name='' method='POST' >
    <lalebl>".__('Site Password','wpccp')."</label>
    <input type='password' name='wpcpp_global_password' />
    <input type='submit' value='".__('Verify','wpccp')."' /></form>";

    echo $html;exit;
}