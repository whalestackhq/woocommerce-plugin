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

		if (!class_exists( 'woocommerce' ) ) {
			add_action( 'admin_notices', array($this, 'woocommerce_coinqvest_missing_wc_notice') );
			return;
		}

		$this->cq_wc_gateway = new WC_Gateway_Coinqvest($this->plugin_name_url, $this->plugin_basename);

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

			add_filter( 'woocommerce_payment_gateways', array($this, 'cq_wc_add_coinqvest_class' ));

		}

	}

	public function woocommerce_coinqvest_missing_wc_notice() {
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'COINQVEST requires WooCommerce to be installed and active. You can download %s here.', 'coinqvest' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
	}

	public function cq_wc_add_coinqvest_class( $methods ) {
		$methods[] = $this->cq_wc_gateway;
		return $methods;
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/coinqvest-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {
		$params = array ( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		wp_enqueue_script( 'coinqvest_ajax_handle', plugin_dir_url( __FILE__ ) . 'js/coinqvest-admin-ajax-handler.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'coinqvest_ajax_handle', 'params', $params );
	}


	/**
	 * form submits are handled here, both Ajax and POST (fallback if Ajax doesn't work)
	 */
	public function admin_form_response_handler() {

		if ( !is_user_logged_in() ) {
			exit;
		}

		$nonce = $_POST['_wpnonce'];
		$task = $_POST['task'];

//		switch ($task) {
//
//			case 'submit_api_settings':
//
//				if ( ! wp_verify_nonce( $nonce, 'submitApiSettings-23iyj@h!' ) ) {
//					exit;
//				}
//				$this->settings = new Settings();
//				$this->settings->submit_form_api_settings();
//				break;
//
//			case 'submit_global_settings':
//
//				if ( ! wp_verify_nonce( $nonce, 'submitGlobalSettings-abg3@9' ) ) {
//					exit;
//				}
//				$this->settings = new Settings();
//				$this->settings->submit_form_global_settings();
//				break;
//
//			case 'add_payment_button':
//
//				if ( ! wp_verify_nonce( $nonce, 'addPaymentButton-dfs!%sd' ) ) {
//					exit;
//				}
//				$this->add_payment_button = new Add_Payment_Button();
//				$this->add_payment_button->submit_form_add_payment_button();
//				break;
//
//			case 'edit_payment_button':
//
//				if ( ! wp_verify_nonce( $nonce, 'editPaymentButton-dfs!%sd' ) ) {
//					exit;
//				}
//				$this->edit_payment_button = new Edit_Payment_Button();
//				$this->edit_payment_button->submit_form_edit_payment_button();
//				break;
//
//		}

	}

	/**
	 * Admin notices when form submit with POST (adds success/error parameters to URL)
	 */
	public function print_plugin_admin_notices() {
		$this->admin_notices = new Admin_Helpers();
		$this->admin_notices->print_plugin_admin_notices();
	}



}