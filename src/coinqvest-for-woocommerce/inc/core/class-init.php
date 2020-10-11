<?php

namespace WC_COINQVEST\Inc\Core;
use WC_COINQVEST as CQ;
use WC_COINQVEST\Inc\Admin as Admin;
use WC_COINQVEST\Inc\Frontend as Frontend;

class Init {

	protected $loader;
	protected $plugin_name;
	protected $version;
	protected $plugin_text_domain;
	protected $plugin_name_url;
	protected $plugin_name_dir;
	protected $plugin_basename;

	// define the core functionality of the plugin.
	public function __construct() {

		$this->plugin_name = CQ\PLUGIN_NAME;
		$this->version = CQ\PLUGIN_VERSION;
		$this->plugin_basename = CQ\PLUGIN_BASENAME;
		$this->plugin_text_domain = CQ\PLUGIN_TEXT_DOMAIN;
		$this->plugin_name_url =CQ\PLUGIN_NAME_URL;
		$this->plugin_name_dir =CQ\PLUGIN_NAME_DIR;
		$this->plugin_basename = CQ\PLUGIN_BASENAME;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Loads the following required dependencies for this plugin.
	 *
	 * - Loader - Orchestrates the hooks of the plugin.
	 * - Internationalization_i18n - Defines internationalization functionality.
	 * - Admin - Defines all hooks for the admin area.
	 * - Frontend - Defines all hooks for the public side of the site.
	 *
	 * @access    private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Internationalization_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access    private
	 */
	private function set_locale() {

		$plugin_i18n = new Internationalization_i18n($this->plugin_text_domain);

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * Callbacks are documented in inc/admin/class-admin.php
	 * 
	 * @access    private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin\Admin($this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain(), $this->get_plugin_name_url(), $this->get_plugin_basename());

		$this->loader->add_action('plugins_loaded', $plugin_admin, 'init_wc_coinqvest');

	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access    private
	 */
	private function define_public_hooks() {

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_version() {
		return $this->version;
	}

	public function get_plugin_text_domain() {
		return $this->plugin_text_domain;
	}

	public function get_plugin_name_url() {
		return $this->plugin_name_url;
	}

	public function get_plugin_name_dir() {
		return $this->plugin_name_dir;
	}

	public function get_plugin_basename() {
		return $this->plugin_basename;
	}

    public function get_plugin_data() {
	    global $wp_version;
	    $wp = 'WP ' . $wp_version;
	    $woo = 'Woo ' . $this->get_woo_version();
        $cq = 'CQ ' . $this->get_version();
	    return $wp . ', ' . $woo . ', ' . $cq;
    }

    public function get_woo_version() {
        if ( class_exists( 'WooCommerce' ) ) {
            global $woocommerce;
            return $woocommerce->version;

        }
        return 'n/a';
    }

}
