<?php

namespace WC_COINQVEST\Inc\Admin;

class Admin {

	private $plugin_name;
	private $version;
	private $plugin_text_domain;
	private $plugin_name_url;
	private $plugin_basename;
	private $cq_wc_gateway;

	public function __construct($plugin_name, $version, $plugin_text_domain, $plugin_name_url, $plugin_basename) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;
		$this->plugin_name_url = $plugin_name_url;
		$this->plugin_basename = $plugin_basename;

	}

	public function init_wc_coinqvest() {

		if (!class_exists('woocommerce')) {
			add_action('admin_notices', array($this, 'woocommerce_coinqvest_missing_wc_notice'));
			return;
		}

		$this->cq_wc_gateway = new WC_Gateway_Coinqvest($this->plugin_name_url, $this->plugin_basename, $this->version);

		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

			add_filter('woocommerce_payment_gateways', array($this, 'cq_wc_add_coinqvest_class'));

		}

	}

	public function woocommerce_coinqvest_missing_wc_notice() {
		echo '<div class="error"><p><strong>' . sprintf(esc_html__('COINQVEST requires WooCommerce to be installed and active. You can download %s here.', 'coinqvest'), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>') . '</strong></p></div>';
	}

	public function cq_wc_add_coinqvest_class($methods) {
		$methods[] = $this->cq_wc_gateway;
		return $methods;
	}

}