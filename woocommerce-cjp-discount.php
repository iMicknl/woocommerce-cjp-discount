<?php
/**
 * Plugin Name: WooCommerce CJP discount
 * Description: Extension which allows discount for Cultureel Jongeren Paspoort (CJP) cardholders
 * Version: 1.0.0
 * Author: codeconverters
 * Author URI: https://www.codeconverters.nl
 * Text Domain: woocommerce-cjp-discount
 *
 * Requires at least: 4.6
 * Tested up to: 4.8
 * WC Tested up: 3.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

define( 'CJP_DISCOUNT_PATH', __FILE__ );

require_once( 'includes/class-admin.php' );
require_once( 'includes/class-cjp-coupon.php' );
require_once( 'includes/class-ajax.php' );
require_once( 'includes/class-checkout.php' );

new \WooCommerce_CJP\Checkout();

if ( is_admin() ) {
	new \WooCommerce_CJP\Admin();
}