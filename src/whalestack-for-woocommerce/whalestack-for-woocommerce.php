<?php

/**
 * Plugin Name: Whalestack for WooCommerce
 * Description: Cryptocurrency and Stablecoins (EURC, USDC) Payment Processor - Accept digital payments from your customers and instantly settle in your preferred currency like USD, EUR, or BRL.
 * Author: Whalestack LLC
 * Author URI: https://www.whalestack.com/
 * Version: 2.0.0
 * License: Apache 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text domain: whalestack-for-woocommerce
 * Domain Path: /languages
 *
 * WC tested up to: 8.3.1
 */

namespace WC_Whalestack;

defined('ABSPATH') or exit;

/**
 * Define Constants
 */
define(__NAMESPACE__ . '\NS', __NAMESPACE__ . '\\');
define(NS . 'PLUGIN_NAME', 'whalestack-for-woocommerce');
define(NS . 'PLUGIN_VERSION', '2.0.0');
define(NS . 'PLUGIN_NAME_DIR', plugin_dir_path(__FILE__));
define(NS . 'PLUGIN_NAME_URL', plugin_dir_url(__FILE__));
define(NS . 'PLUGIN_BASENAME', plugin_basename(__FILE__));
define(NS . 'PLUGIN_TEXT_DOMAIN', 'whalestack-for-woocommerce');

/**
 * Autoload Classes
 */
require_once(PLUGIN_NAME_DIR . 'inc/libraries/autoloader.php');

/**
 * Register Activation and Deactivation Hooks
 */
register_activation_hook(__FILE__, array(NS . 'Inc\Core\Activator', 'activate'));
register_deactivation_hook(__FILE__, array(NS . 'Inc\Core\Deactivator', 'deactivate'));

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

/**
 * Plugin Singleton Container
 */
class WC_Whalestack {

	static $init;

	public static function init() {

		if (null == self::$init) {
			self::$init = new Inc\Core\Init();
			self::$init->run();
		}

		return self::$init;
	}

}

function whalestackInit() {
	return WC_Whalestack::init();
}

$min_php = '5.6.0';

// Check the minimum required PHP version and run the plugin.
if (version_compare(PHP_VERSION, $min_php, '>=')) {
	whalestackInit();
}