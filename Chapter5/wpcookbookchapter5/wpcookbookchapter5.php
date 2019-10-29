<?php
/**
 * Plugin Name: WPCookbook Chapter 5
 * Plugin URI: 
 * Description: Chapter 5 code implementations.
 * Version: 1.0
 * Author: Rakhitha Nimesh
 * Author URI: 
 * Author Email: 
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpccp
 * Domain Path: /languages
 */


add_action('show_user_profile', 'wpccp_chapter5_user_extra_fields');
add_action('edit_user_profile', 'wpccp_chapter5_user_extra_fields');

add_action('personal_options_update', 'wpccp_chapter5_save_user_extra_fields', 9999);
add_action('edit_user_profile_update',  'wpccp_chapter5_save_user_extra_fields');


function wpccp_chapter5_user_extra_fields($user){
    if ( current_user_can('edit_user', $user->ID) ) {

        $job_title = get_user_meta($user->ID, 'wpccp_profile_job',true);
        $job_category = get_user_meta($user->ID, 'wpccp_profile_job_category',true);
        $job_desc = get_user_meta($user->ID, 'wpccp_profile_job_desc',true);

        $display = "<table class='form-table'>";
        $display .= "<tr><th>".__('Job Title','wpccp')."</th>
                         <td><input type='text' name='wpccp_profile_job' value='".$job_title."' /></td></tr>";

        $display .= "<tr><th>".__('Job Category','wpccp')."</th>
                         <td><select name='wpccp_profile_job_category'  >
                            <option ".selected('0',$job_category,true)." value='0'>".__('Please Select','wpccp')."</option>
                            <option ".selected('accounting',$job_category,true)." value='accounting'>".__('Accounting','wpccp')."</option>
                            <option ".selected('banking',$job_category,true)." value='banking'>".__('Banking','wpccp')."</option>
                            </select></td></tr>";


        $display .= "<tr><th>".__('Job Description','wpccp')."</th>
                         <td><textarea name='wpccp_profile_job_desc'  >".wp_kses_post($job_desc)."</textarea></td></tr>";

        $display .= "</table>";
        echo $display;
    }
}

function wpccp_chapter5_save_user_extra_fields($user_id){
    if ($_POST && current_user_can('edit_user', $user_id ) ) {

        $job_title = isset($_POST['wpccp_profile_job']) ? sanitize_text_field($_POST['wpccp_profile_job']) : '';
        $job_category = isset($_POST['wpccp_profile_job_category']) ? sanitize_text_field($_POST['wpccp_profile_job_category']) : '';
        $job_desc = isset($_POST['wpccp_profile_job_desc']) ? wp_kses_post($_POST['wpccp_profile_job_desc']) : '';
    
        update_user_meta($user_id, 'wpccp_profile_job',$job_title);
        update_user_meta($user_id, 'wpccp_profile_job_category',$job_category);
        update_user_meta($user_id, 'wpccp_profile_job_desc',$job_desc);
    }
}


add_action('after_setup_theme', 'wpccp_chapter5_remove_admin_bar'); 
function wpccp_chapter5_remove_admin_bar() {
    if ( ! current_user_can('administrator') ) {
      show_admin_bar(false);
    }
}

// add_action('admin_init', 'wpccp_chapter5_remove_backend_access'); 
function wpccp_chapter5_remove_backend_access() {
    if ( ! current_user_can('administrator') && is_admin() ) {
      die('Permission Denied');
    }
}


add_action('admin_footer', 'wpccp_chapter5_user_action_buttons');
add_action('load-users.php', 'wpccp_chapter5_users_page_loaded');

function wpccp_chapter5_user_action_buttons() {
    $screen = get_current_screen();
    if ( $screen->id != "users" )  
        return;
     
    $mark_as_staff                = __('Mark as Staff','wpccp');       
    $approve_profile                = __('Approve Profile','wpccp');

?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('<option>').val('wpccp_mark_staff_user').text("<?php echo $mark_as_staff; ?>").appendTo("select[name='action']");
            $('<option>').val('wpccp_mark_staff_user').text("<?php echo $mark_as_staff; ?>").appendTo("select[name='action2']");

            $('<option>').val('wpccp_apr_user').text("<?php echo $approve_profile; ?>").appendTo("select[name='action']");
            $('<option>').val('wpccp_apr_user').text("<?php echo $approve_profile; ?>").appendTo("select[name='action2']");
        });
    </script>
    <?php
}

function wpccp_chapter5_users_page_loaded() {
    if( ! current_user_can('manage_options') )  {                
        return;
    }

    if( (isset($_GET['action']) && $_GET['action'] === 'wpccp_mark_staff_user') ||
        (isset($_GET['action2']) && $_GET['action2'] === 'wpccp_mark_staff_user')) { 
        $users = isset($_GET['users']) ? $_GET['users'] : '';  
        if ('' != $users) {  
            foreach ($users as $user ) {
                $user = (int) $user;
                $meta = get_user_meta($user, 'wpccp_user_staff_status', true); 
                if('Approved' != $meta){
                    update_user_meta($user, 'wpccp_user_staff_status', 'Staff'); 
                }
            }            
        }
    }

    if( (isset($_GET['action']) && $_GET['action'] === 'wpccp_apr_user') ||
        (isset($_GET['action2']) && $_GET['action2'] === 'wpccp_apr_user')) { 
        $apr_users = isset($_GET['users']) ? $_GET['users'] : '';  
        if ('' != $apr_users) {  
            foreach ($apr_users as $apr_user ) {
                $apr_user = (int) $apr_user;
                $meta = get_user_meta($apr_user, 'wpccp_user_approval_status', true); 
                if('Approved' != $meta){
                    update_user_meta($apr_user, 'wpccp_user_approval_status', 'Approved'); 
                }
            }            
        }
    }
}

add_action( 'user_register', 'wpccp_chapter5_add_registration_data', 10, 1 );
function wpccp_chapter5_add_registration_data( $user_id ) {

    
    $activation_status = get_user_meta($user_id, 'wpccp_user_activation_status',true);
    update_user_meta($user_id, 'wpccp_user_approval_status','Pending Approval');

    if( trim($activation_status) == '' ){
    	$user_info = get_userdata($user_id);
	    $code = md5(time());
	    $string = array('id'=>$user_id , 'code'=>$code);

	    update_user_meta($user_id, 'wpccp_user_activation_status', 'Pending Activation');
	    update_user_meta($user_id, 'wpccp_user_activation_code', $code);

	    $url = get_site_url(). '/wp-login.php/?activation_code=' .base64_encode( serialize($string));

	    $html = __('Please click the following link to activate your account ','wpccp') .' <br/><br/> <a href="'.$url.'">'.$url.'</a>';

	    wp_mail( $user_info->user_email, __('WP Cookbook User Activation','wpccp') , $html);
    }
}

function wpccp_chapter5_add_login_message() {

	$activation_code = isset($_GET['activation_code']) ? $_GET['activation_code'] : '';

	if($activation_code != '' ){
		$data = unserialize(base64_decode($activation_code));

    	$activation_code_filtered = get_user_meta( $data['id'], 'wpccp_user_activation_code', true);

    	if( $data['code'] ==  $activation_code_filtered){ 
    		update_user_meta($data['id'], 'wpccp_user_activation_status', 'Active');
    		
    		$message =  "<p class='message'>".__('Activation successful. Please login to your account.','wpccp')."</p>";
    	}else{
    		$message =  "<p class='message'>".__('Activation failed.','wpccp')."</p>";
    	}	
	}

	return $message;
}
add_filter('login_message', 'wpccp_chapter5_add_login_message');


function wpccp_chapter5_login_validation($user, $password){

	if( isset( $user->ID ) ){
		$activation_status = get_user_meta($user->ID, 'wpccp_user_activation_status', true );
		
		if( $activation_status == 'Pending Activation' ){
			$user = new WP_Error( 'denied', __("Account is pending activation.") );
		}

		$approval_status = get_user_meta($user->ID, 'wpccp_user_approval_status', true );
		
		if( $approval_status == 'Pending Approval' ){
			$user = new WP_Error( 'denied', __("Account is pending approval.") );
		}
	}

    return $user;
}
add_filter( 'wp_authenticate_user', 'wpccp_chapter5_login_validation', 10, 2 );


add_filter('manage_users_columns', 'wpccp_chapter5_user_custom_columns');
function wpccp_chapter5_user_custom_columns( $column ) {
    $column['wpccp_act_status'] = __('Activation Status','wpccp');
    return $column;
}

add_action('manage_users_custom_column', 'wpccp_chapter5_user_custom_column_values', 10, 3);
function wpccp_chapter5_user_custom_column_values( $val, $column_name, $user_id ) {
    $wpccp_user_approval_status = get_user_meta( $user_id , 'wpccp_user_approval_status', TRUE);
    if($wpccp_user_approval_status == 'Approved'){
        $wpccp_user_approval_status = __('Approved','wpccp');
    }else if($wpccp_user_approval_status == 'Pending Approval'){
        $wpccp_user_approval_status = __('Pending Approval','wpccp');
    }
         
    switch ($column_name) {
        case 'wpccp_apr_status' :
            return $wpccp_user_approval_status;
            break; 
        default:
            return $val;
            break;
    }
}



add_shortcode('wpccp_recent_user_list', 'wpccp_chapter5_recent_user_list');
function wpccp_chapter5_recent_user_list($atts,$content){

 $user_query = new WP_User_Query( array(
         'orderby' => 'registered',
         'order' => 'ASC',
         'number' => 5
     ) );

 $user_list = $user_query->get_results();
 $user_list_html = '<ul>';
 if(count($user_list) > 0){      
     foreach ($user_list as $key => $user) {
         $user_list_html .= '<li>'. $user->user_login .'</li>';
     }
 }else{
     $user_list_html .= '<li>'.__('No Users Found','wpccp') .'</li>';
 }
 $user_list_html .= '</ul>';
 return $user_list_html;
}




add_shortcode('wpccp_user_search', 'wpccp_chapter5_user_search');
function wpccp_chapter5_user_search($atts,$content){

	$search_val = '';
	if(isset($_POST['wpccp_user_search'])){
		$search_val = sanitize_text_field($_POST['wpccp_user_search']);

		$user_query = new WP_User_Query( array(
		    'meta_query' => array(
		        'relation' => 'OR',
		        array(
		            'key'     => 'first_name',
		            'value'   => $search_val,
		            'compare' => 'LIKE'
		        ),
		        array(
		            'key'     => 'last_name',
		            'value'   => $search_val,
		            'compare' => 'LIKE'
		        )
		    )
		) );
		$user_list = $user_query->get_results();
		$user_list_html = '<ul>';
		if(count($user_list) > 0){		
			foreach ($user_list as $key => $user) {
				$user_list_html .= '<li>'.get_user_meta($user->ID, 'first_name', true) . ' ' . get_user_meta($user->ID, 'last_name', true) .'</li>';
			}
		}else{
			$user_list_html .= '<li>'.__('No Users Found','wpccp') .'</li>';
		}
		$user_list_html .= '</ul>';
	}

	$display = "<form method='POST' >
					<span>".__('Search ','wpccp')."
					<input type='text' value='".$search_val."' name='wpccp_user_search' />
					<input type='submit' value='".__('Search Users','wpccp')."' />
				</form>";

	$display .= $user_list_html;

	return $display;
}


