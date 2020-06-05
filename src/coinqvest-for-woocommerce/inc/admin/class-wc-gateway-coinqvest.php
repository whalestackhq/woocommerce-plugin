<?php
namespace WC_COINQVEST\Inc\Admin;
use WC_COINQVEST\Inc\Libraries\Api\CQ_Logging_Service;
use WC_Payment_Gateway;

defined( 'ABSPATH' ) or exit;

class WC_Gateway_Coinqvest extends WC_Payment_Gateway {

	private $plugin_name_url;
	private $plugin_basename;
	private $api_key;
	private $api_secret;

	public function __construct($plugin_name_url, $plugin_basename) {

		$this->plugin_name_url = $plugin_name_url;
		$this->plugin_basename = $plugin_basename;

		$this->id = 'wc_coinqvest';
		$this->has_fields = false; // Bool. Can be set to true if you want payment fields to show on the checkout (if doing a direct integration).
		$this->order_button_text  = __( 'Proceed to COINQVEST', 'coinqvest' );
		$this->method_title = 'COINQVEST';
		$this->method_description = __( 'Accept payments in crypto (BTC, ETH, XRP, XLM, LTC) and instantly settle in your local currency (USD, EUR, CAD, NGN).', 'coinqvest' );

		// Define user set variables.
		$this->title = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );
		$this->api_key = $this->get_option('api_key');
		$this->api_secret = $this->get_option('api_secret');
		$this->debug = 'yes' === $this->get_option( 'debug', 'no' );
		CQ_Logging_Service::$log_enabled = $this->debug;

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_api_wc_coinqvest', array( $this, 'handle_webhook' ) );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'plugin_action_links' ) );

	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {

		$form_fields = new WC_Coinqvest_Admin_Form();
		$this->form_fields = $form_fields->form_fields($this->api_key, $this->api_secret);
	}

	/**
	 * Get gateway icon.
	 */
	public function get_icon() {

		if ( $this->get_option( 'show_icons' ) === 'no' ) {
			return '';
		}

		$url = $this->plugin_name_url . 'assets/images/wc-cq-logo.png';
		$icon = '<img width="130px" src="' . esc_attr( $url ) . '" />';

		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
	}

	/**
	 * Add settings link on plugin page
	 */
	public function plugin_action_links($links) {
		$plugin_links = array(
			'<a href="admin.php?page=wc-settings&tab=checkout&section=wc_coinqvest">' . esc_html__( 'Settings', 'coinqvest-for-woocommerce' ) . '</a>',
		);
		return array_merge($plugin_links, $links);
	}

	/**
	 * Init settings for gateways.
	 */
	public function init_settings() {
		parent::init_settings();
		$this->enabled = ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'] ? 'yes' : 'no';
	}

	/**
	 * Create the checkout
	 */
	public function process_payment($order_id) {

		$options['api_key'] = $this->api_key;
		$options['api_secret'] = $this->api_secret;
		$options['settlement_currency'] = $this->get_option('settlement_currency');

		$charge = new WC_Coinqvest_Checkout();
		return $charge->create_checkout($order_id, $options);
	}

	/**
	 * Handle requests sent to webhook.
	 */
	public function handle_webhook() {

		$webhook_handler = new WC_Coinqvest_Webhook_Handler($this->api_secret);
		$webhook_handler->handle_webhook();
	}

}