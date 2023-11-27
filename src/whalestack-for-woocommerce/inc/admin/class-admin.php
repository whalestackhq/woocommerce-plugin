<?php

namespace WC_Whalestack\Inc\Admin;

class Admin {

	private $plugin_name;
	private $version;
	private $plugin_text_domain;
	private $plugin_name_url;
	private $plugin_basename;
	private $whalestack_wc_gateway;

	public function __construct($plugin_name, $version, $plugin_text_domain, $plugin_name_url, $plugin_basename) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;
		$this->plugin_name_url = $plugin_name_url;
		$this->plugin_basename = $plugin_basename;

	}

	public function init_wc_whalestack() {

		if (!class_exists('woocommerce')) {
			add_action('admin_notices', array($this, 'woocommerce_whalestack_missing_wc_notice'));
			return;
		}

		$this->whalestack_wc_gateway = new WC_Gateway_Whalestack($this->plugin_name_url, $this->plugin_basename, $this->version);

		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

			add_filter('woocommerce_payment_gateways', array($this, 'whalestack_wc_add_whalestack_class'));

		}

	}

	public function woocommerce_whalestack_missing_wc_notice() {
		echo '<div class="error"><p><strong>' . sprintf(esc_html__('Whalestack requires WooCommerce to be installed and active. You can download %s here.', 'whalestack'), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>') . '</strong></p></div>';
	}

	public function whalestack_wc_add_whalestack_class($methods) {
		$methods[] = $this->whalestack_wc_gateway;
		return $methods;
	}

}