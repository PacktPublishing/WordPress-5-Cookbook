<?php
get_header();
global $wpcpp_user_id;

$user_info = get_userdata($wpcpp_user_id);
$full_name = get_user_meta($wpcpp_user_id, 'first_name', true) . " " . get_user_meta($wpcpp_user_id, 'last_name', true);
?>

 <section id="primary" class="content-area">
 <main id="main" class="site-main" style="width: 50%;margin:auto;">
 <table>
 <tr><td><?php _e('Username','wpccp_ch7'); ?></td><td><?php echo $user_info->user_login; ?></td></tr>
 <tr><td><?php _e('Email','wpccp_ch7'); ?></td><td><?php echo $user_info->user_email; ?></td></tr>
 <tr><td><?php _e('Full Name','wpccp_ch7'); ?></td><td><?php echo $full_name; ?></td></tr>
 </table>
 </main><!-- #main -->
 </section><!-- #primary -->

<?php
get_footer();