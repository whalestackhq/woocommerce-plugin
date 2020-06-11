<?php
namespace WC_COINQVEST\Inc\Admin;

use WC_COINQVEST\Inc\Libraries\Api\CQ_Logging_Service;

defined('ABSPATH') or exit;

class WC_Coinqvest_Webhook_Handler {

	protected $api_secret;

	public function __construct($api_secret) {

		$this->api_secret = $api_secret;

		add_filter('woocommerce_order_data_store_cpt_get_orders_query', array($this, 'get_order_by_checkout_id'), 10, 2);
	}

	/**
	 * Handle requests sent to webhook.
	 */
	public function handle_webhook() {

		if (('POST' !== $_SERVER['REQUEST_METHOD'])
		     || !isset($_GET['wc-api'])
		     || ('WC_COINQVEST' !== $_GET['wc-api'])
		) {
			return;
		}

		$payload = file_get_contents('php://input');
		$request_headers = array_change_key_case($this->get_request_headers(), CASE_UPPER);

		if (!empty($payload) && $this->validate_webhook($request_headers, $payload)) {

			$payment = json_decode($payload, true);

			$orders = wc_get_orders(array('_coinqvest_checkout_id' => $payment['checkoutId']));

			if (empty($orders)) {
				exit;
			}

			$order = new \WC_Order($orders[0]);
			$this->_update_order_status($order, $payment);

			status_header(200);
			exit;

		} else {

			CQ_Logging_Service::write('Incoming webhook failed validation: ' . print_r( $payload, true));

			status_header(400);
			exit;
		}
	}

	/**
	 * Handle a custom '_coinqvest_checkout_id' query var to get orders with the '_coinqvest_checkout_id' meta.
	 * @param array $query - Args for WP_Query.
	 * @param array $query_vars - Query vars from WC_Order_Query.
	 * @return array modified $query
	 */
	public function get_order_by_checkout_id($query, $query_vars) {

		if (!empty($query_vars['_coinqvest_checkout_id'])) {
			$query['meta_query'][] = array(
				'key' => '_coinqvest_checkout_id',
				'value' => esc_attr($query_vars['_coinqvest_checkout_id']),
			);
		}

		return $query;
	}

	/**
	 * Validate the webhook request
	 */
	public function validate_webhook($request_headers, $payload) {

		if (!isset($request_headers['X-WEBHOOK-AUTH'])) {
			return false;
		}

		$sig = $request_headers['X-WEBHOOK-AUTH'];

		$api_secret = $this->api_secret;

		$sig2 = hash('sha256', $api_secret . $payload);

		if ($sig === $sig2) {
			return true;
		}

		return false;
	}

	/**
	 * Gets the incoming request headers. Some servers are not using
	 * Apache and "getallheaders()" will not work so we may need to
	 * build our own headers.
	 */
	public function get_request_headers() {
		if (!function_exists('getallheaders')) {
			$headers = array();

			foreach ($_SERVER as $name => $value ) {
				if ('HTTP_' === substr($name, 0, 5)) {
					$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
				}
			}

			return $headers;
		} else {
			return getallheaders();
		}
	}

	/**
	 * Update the status of an order.
	 * @param  WC_Order $order
	 */
	public function _update_order_status($order, $payment) {

		CQ_Logging_Service::write('Webhook Payload: ' . print_r($payment, true));

		$wc_order_state = $order->get_status();
		$cq_payment_state = $payment['state'];

		if (in_array($wc_order_state, array('processing', 'completed'))) {
			return;
		}

		$payment_page = 'https://www.coinqvest.com/en/payment/' . $payment['id'];

		if ($wc_order_state == 'pending' && $cq_payment_state == 'RESOLVED') {

			$order->update_status('processing', __( 'COINQVEST payment was successfully processed.', 'coinqvest'));
			$order->add_order_note(sprintf(__( 'Find COINQVEST payment details <a href="%s" target="_blank">here</a>.', 'coinqvest'), esc_url($payment_page )));
			$order->payment_complete();
		}

		if ($wc_order_state == 'pending' && $cq_payment_state == 'UNRESOLVED') {

			$order->update_status('on-hold', __('COINQVEST payment was processed, but some generic error occurred.', 'coinqvest'));
			$order->add_order_note(__('COINQVEST payment was processed, but some generic error occurred. Please contact your account manager.', 'coinqvest'));
		}

		if ($wc_order_state == 'canceled' && $cq_payment_state == 'RESOLVED') {

			$order->update_status('processing', __('COINQVEST payment was successfully processed.', 'coinqvest'));
            $order->add_order_note(sprintf(__('Find COINQVEST payment details <a href="%s" target="_blank">here</a>.', 'coinqvest'), esc_url($payment_page )));
			$order->payment_complete();
		}

		if ($wc_order_state == 'canceled' && $cq_payment_state == 'UNRESOLVED') {

            $order->update_status('on-hold', __('COINQVEST payment was processed, but some generic error occurred.', 'coinqvest'));
            $order->add_order_note(__('COINQVEST payment was processed, but some generic error occurred. Please contact your account manager.', 'coinqvest'));
		}

        $order->update_meta_data('_coinqvest_payment_id', esc_attr($payment['id']));
        $order->save();

	}

}