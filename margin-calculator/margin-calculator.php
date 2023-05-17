<?php
/**
 * Plugin Name:     Margin Calculator And Consolidate Price
 * Plugin URI:      https://makuracreations.com
 * Description:     Margin Calculator Plugin Developed By Saroj  
 * Author:          Saroj Dhungana
 * Author URI:      dhunganasaroj.com.np
 * Text Domain:     margin-calculator
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Margin_Calculator
 * 
 * 
 */

 /*
    === Plugin Name ===
        Contributors: (this should be a list of wordpress.org userid's)
        Tags: comments, spam
        Requires at least: 4.0.1
        Tested up to worpdress: 5.8.2
        Requires PHP: 5.6
        License: GPLv3 or later License
        URI: http://www.gnu.org/licenses/gpl-3.0.html

 */

 

// Your code starts here.

 if (!defined('ABSPATH')) { exit; }
 
/**
 * Activate the plugin.
 */
function pluginprefix_activate() { 
    flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'pluginprefix_activate' );


/**
 * Deactivation hook.
 */
function pluginprefix_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pluginprefix_deactivate' );

define( 'MARGIN_PLUGIN_DIR', plugin_dir_path( dirname( __FILE__ ) ) );

// Test to see if WooCommerce is active (including network activated).
$plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';

if (
    in_array( $plugin_path, wp_get_active_and_valid_plugins() )
    || in_array( $plugin_path, wp_get_active_network_plugins() )
) {
    //require the custom settings class

    //require_once trailingslashit(WP_PLUGIN_DIR) . 'margin-calculator/includes/admin/class-woo-custom-settings.php';
    require_once trailingslashit(WP_PLUGIN_DIR) . 'margin-calculator/includes/admin/class-custom-margin-calculator.php';
    require_once trailingslashit(WP_PLUGIN_DIR) . 'margin-calculator/includes/admin/class-individual-product-settings.php';
    require_once trailingslashit(WP_PLUGIN_DIR) . 'margin-calculator/includes/cartQuantity/class-cart-quantity-price-breakdown.php';

    
}
