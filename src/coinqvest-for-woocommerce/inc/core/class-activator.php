<?php

namespace WC_COINQVEST\Inc\Core;

class Activator {

	public static function activate() {

		$min_php = '5.6.0';

		// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
		if (version_compare(PHP_VERSION, $min_php, '<')) {
		    deactivate_plugins( plugin_basename( __FILE__ ) );
		    wp_die('This plugin requires a minimum PHP Version of ' . $min_php);
		}
	}


}
