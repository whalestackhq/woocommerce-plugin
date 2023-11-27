<?php
namespace WC_Whalestack\Inc\Admin;
use WC_Whalestack\Inc\Libraries\Api;
use WC_Payment_Gateway;

defined('ABSPATH') or exit;

class WC_Gateway_Whalestack extends WC_Payment_Gateway {

	private $plugin_name_url;
	private $plugin_basename;
	private $version;
	private $api_key;
	private $api_secret;

	public function __construct($plugin_name_url, $plugin_basename, $version) {

		$this->plugin_name_url = $plugin_name_url;
		$this->plugin_basename = $plugin_basename;
		$this->version = $version;

		$this->id = 'wc_whalestack';
		$this->has_fields = false;
		$this->order_button_text  = __('Place order now', 'whalestack');
		$this->method_title = 'Whalestack';
        $this->method_description = sprintf( __('Accept crypto and stablecoin payments from your customers and instantly settle in your preferred digital currency. <br /><a href="%1$s" target="_blank">Sign up</a> for a Whalestack account and <a href="%2$s" target="_blank">get your API credentials</a>.', 'whalestack'), 'https://www.whalestack.com', 'https://www.whalestack.com/en/api-settings');

        // Define user set variables.
		$this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->api_key = $this->get_option('api_key');
		$this->api_secret = $this->get_option('api_secret');
		$this->debug = 'yes' === $this->get_option('debug', 'no');
		Api\WS_Logging_Service::$log_enabled = $this->debug;

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		add_action('wp_enqueue_scripts', array($this, 'payment_scripts'));
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action('woocommerce_api_wc_whalestack', array($this, 'handle_webhook'));
		add_filter('plugin_action_links_' . $plugin_basename, array($this, 'plugin_action_links'));
		add_action('woocommerce_admin_order_data_after_order_details', array($this, 'display_whalestack_payment_data_in_order'));

    }

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {

        $form_fields = new WC_Whalestack_Admin_Form();
		$this->form_fields = $form_fields->form_fields($this->api_key, $this->api_secret);
	}

    /**
     * Settings form fields input validation
     */
    public function validate_api_key_field($key, $value) {

        $value = sanitize_text_field($value);

        if ($value == '') {
            \WC_Admin_Settings::add_error(esc_html(__('Please enter your API key.', 'whalestack')));
        }
        if (!empty($value) && strlen($value) != 12) {
            \WC_Admin_Settings::add_error(esc_html(__('API Key seems to be wrong. Please double check.', 'whalestack')));
        }
        return $value;
    }

    public function validate_api_secret_field($key, $value) {

        $value = sanitize_text_field($value);

        if ($value == '') {
            \WC_Admin_Settings::add_error(esc_html(__('Please enter your API secret.', 'whalestack')));
        }
        if (!empty($value) && strlen($value) != 29) {
            \WC_Admin_Settings::add_error(esc_html(__('API Secret seems to be wrong. Please double check.', 'whalestack')));
        }
        return $value;
    }

    public function validate_settlement_currency_field($key, $value) {

        if ($this->api_key == '' && $this->api_secret == '') {
            return $value;
        }

        $value = sanitize_text_field($value);

        if ($value == '0') {
            \WC_Admin_Settings::add_error(esc_html(__('Please select a settlement currency.', 'whalestack')));
            return $this->get_option('settlement_currency');
        }

        return $value;
    }

	/**
	 * Get gateway icon
	 */
	public function get_icon() {

		if ($this->get_option('show_icons') === 'no') {
			return '';
		}

		$url = $this->plugin_name_url . 'assets/images/wc-whalestack-logo.png';
		$icon = '<img class="whalestack-checkout-logo" src="' . esc_attr($url) . '" />';

		return apply_filters('woocommerce_gateway_icon', $icon, $this->id);
	}

	/**
	 * Add settings link on plugin page
	 */
	public function plugin_action_links($links) {

		$plugin_links = array(
			'<a href="admin.php?page=wc-settings&tab=checkout&section=wc_whalestack">' . esc_html__('Settings', 'whalestack-for-woocommerce') . '</a>',
		);
		return array_merge($plugin_links, $links);
	}

	/**
	 * Init settings for gateways
	 */
	public function init_settings() {

		parent::init_settings();
		$this->enabled = !empty($this->settings['enabled']) && 'yes' === $this->settings['enabled'] ? 'yes' : 'no';
	}

	/**
	 * Create the checkout
	 */
	public function process_payment($order_id) {

		$options['api_key'] = $this->api_key;
		$options['api_secret'] = $this->api_secret;
		$options['settlement_currency'] = $this->get_option('settlement_currency');
        $options['checkout_language'] = $this->get_option('checkout_language');

		$charge = new WC_Whalestack_Checkout();
		return $charge->create_checkout($order_id, $options);
	}

	/**
	 * Handle requests sent to webhook
	 */
	public function handle_webhook() {

		$webhook_handler = new WC_Whalestack_Webhook_Handler($this->api_secret);
		$webhook_handler->handle_webhook();
	}

    /**
     * Adds extra fields in the admin order view
     */
    function display_whalestack_payment_data_in_order($order){

        $whalestack_checkout_id = $order->get_meta('_whalestack_checkout_id', true );
        $whalestack_payment_state = $order->get_meta('_whalestack_payment_state', true );
        $whalestack_underpaid_accepted_price = $order->get_meta('_whalestack_underpaid_accepted_price', true );
        $whalestack_refund_id = $order->get_meta('_whalestack_refund_id', true );
        $display_info = null;

        if (is_null($whalestack_checkout_id)) {
            return;
        }

        switch ($whalestack_payment_state) {

            case 'CHECKOUT_COMPLETED':

                $payment_details_page = 'https://www.whalestack.com/en/payment/checkout-id/' . $whalestack_checkout_id;
                $display_info = '<span style="color: #079047">' . sprintf(__('Payment was successfully completed. Find payment details <a href="%s" target="_blank">here</a>.', 'whalestack'), esc_url($payment_details_page)) . '</span>';
                break;

            case 'CHECKOUT_UNDERPAID':

                $payment_details_page = 'https://www.whalestack.com/en/unresolved-charge/checkout-id/' . $whalestack_checkout_id;
                $display_info = '<span style="color: #cc292f">' . sprintf(__('Action required: Payment was underpaid by customer. Go to the payment details page to resolve it <a href="%s" target="_blank">here</a>.', 'whalestack'), esc_url($payment_details_page)) . '</span>';
                break;

            case 'UNDERPAID_ACCEPTED':

                $payment_details_page = 'https://www.whalestack.com/en/payment/checkout-id/' . $whalestack_checkout_id;
                $display_info = '<span style="color: #079047">' . sprintf(__('This checkout was originally billed at %1$s but underpaid by your customer and manually accepted at %2$s. Find payment details <a href="%3$s" target="_blank">here</a>.', 'whalestack'), esc_attr($order->get_total() . ' ' . $order->get_currency()), esc_attr($whalestack_underpaid_accepted_price), esc_url($payment_details_page)) . '</span>';
                break;

            case 'REFUND_COMPLETED':

                $payment_details_page = 'https://www.whalestack.com/en/refund/' . $whalestack_refund_id;
                $display_info = '<span style="color: #007cba">' . sprintf(__('This order was refunded. Find refund details <a href="%s" target="_blank">here</a>.', 'whalestack'), esc_url($payment_details_page)) . '</span>';
                break;

        }

        ?>
        <p class="form-field form-field-wide">
            <br />
            <h4><?php echo __('Whalestack Payment Details', 'whalestack');?></h4>
            <p><?php echo __('Checkout Id', 'whalestack') . ': ' . esc_html($whalestack_checkout_id);?> </p>
            <p><?php echo $display_info;?> </p>
        </p>
        <?php

    }

    public function payment_scripts() {

        wp_register_style('whalestack_styles', $this->plugin_name_url . 'assets/css/wc-whalestack.css', array(), $this->version, 'all');
        wp_enqueue_style('whalestack_styles');
    }

}