<?php
/**
 * Plugin Name: WPCookbook Chapter 9
 * Plugin URI: 
 * Description: Chapter 9 code implementations.
 * Version: 1.0
 * Author: Rakhitha Nimesh
 * Author URI: 
 * Author Email: 
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpccp
 * Domain Path: /languages
 */

add_action( 'plugins_loaded', 'wpccp_chapter9_register_product_type');
function wpccp_chapter9_register_product_type() {
    if(class_exists('WC_Product')){
        class WC_Product_Simple_WPCCP_Support_Package extends WC_Product {
            public function __construct( $product ) {
                $this->virtual = 'yes';
                $this->downloadable = 'yes';
                parent::__construct( $product );
            }
            public function get_type( ) {
                return  'simple_wpccp_support_package';
            }
        }
    }
}

add_filter( 'product_type_selector', 'wpccp_chapter9_add_product');
function wpccp_chapter9_add_product( $product_types ){
    $product_types[ 'simple_wpccp_support_package' ] = __( 'Support Package' ,'wpccp' );
    return $product_types;
}



add_filter( 'woocommerce_product_data_tabs', 'wpccp_chapter9_hide_data_tabs' );
function wpccp_chapter9_hide_data_tabs( $tabs) {
    $tabs['attribute']['class'][] = 'hide_if_simple_wpccp_support_package';
    $tabs['linked_product']['class'][] = 'hide_if_simple_wpccp_support_package';
    $tabs['shipping']['class'][] = 'hide_if_simple_wpccp_support_package';
    $tabs['advanced']['class'][] = 'hide_if_simple_wpccp_support_package';
    return $tabs;
}

add_action( 'admin_footer', 'wpccp_chapter9_custom_js' );
function wpccp_chapter9_custom_js() {
    if ( 'product' != get_post_type() ){
        return;
    }
    ?>
    <script type='text/javascript'>
        jQuery( document ).ready( function() {
            jQuery("#product-type").change(function(){         
                if(jQuery("#product-type").val() == 'simple_wpccp_support_package'){
                    jQuery( '.options_group.pricing' ).addClass( 'show_if_simple_wpccp_support_package' ).show();
                    jQuery("li.general_options.general_tab").addClass('show_if_simple_wpccp_support_package').show();
                }
            });

            jQuery("#product-type").trigger('change');
        });
    </script><?php
}

add_filter( 'woocommerce_product_data_tabs', 'wpccp_chapter9_product_tabs' );
function wpccp_chapter9_product_tabs( $tabs) {
        $tabs['support'] = array(
            'label'     => __( 'Support Package Data', 'wpccp_ch9' ),
            'target'    => 'simple_wpccp_support_package_options',
            'class'     => array( 'show_if_simple_wpccp_support_package' ),
        );
        return $tabs;
    }

add_action( 'woocommerce_product_data_panels', 'wpccp_chapter9_tab_content' );
function wpccp_chapter9_tab_content() {
        global $post,$wpccp;
        ?>
        <div id='simple_wpccp_support_package_options' class='panel woocommerce_options_panel'><?php
            

            woocommerce_wp_text_input(
                array(
                  'id' => 'wpccp_support_period',
                  'label' => __( 'Support Period', 'dm_product' ),
                  'desc_tip' => 'true',
                  'description' => __( 'Enter support period.', 'wpccp_ch9' ),
                  'type' => 'text',
                  'value' => get_post_meta( $post->ID, 'wpccp_support_period', true )
                )
                );

    ?>

        </div><?php
    }

add_action( 'woocommerce_process_product_meta_simple_wpccp_support_package', 'wpccp_chapter9_save_product_fields'  );
function wpccp_chapter9_save_product_fields( $product_id ) {
    if( current_user_can('manage_options') ){
        $support_period = isset( $_POST['wpccp_support_period'] ) ? $_POST['wpccp_support_period'] : '';
        update_post_meta( $product_id, 'wpccp_support_period', $support_period );
    }
}


