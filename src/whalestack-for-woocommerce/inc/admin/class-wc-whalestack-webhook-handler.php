<?php
namespace WC_Whalestack\Inc\Admin;

use WC_Whalestack\Inc\Libraries\Api\WS_Logging_Service;

defined('ABSPATH') or exit;

class WC_Whalestack_Webhook_Handler {

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
		     || ('WC_Whalestack' !== $_GET['wc-api'])
		) {
			return;
		}

		$payload = file_get_contents('php://input');
		$request_headers = array_change_key_case($this->get_request_headers(), CASE_UPPER);

        if (empty($payload) || !$this->validate_webhook($request_headers, $payload)) {
            WS_Logging_Service::write('Incoming webhook failed validation: ' . print_r( $payload, true));
            status_header(401);
            exit;
        }

        $payload_decoded = json_decode($payload, true);

        // old webhook format which is not used anymore. Just abort with status code 200.
        if (isset($payload_decoded['type'])) {
            status_header(200);
            exit;
        }

        if (!isset($payload_decoded['eventType'])) {
            status_header(400);
            exit;
        }


        $orders = wc_get_orders(array(
            '_whalestack_checkout_id' => $payload_decoded['data']['checkout']['id']
        ));

        if (empty($orders)) {
            status_header(404);
            exit;
        }

        $order = new \WC_Order($orders[0]);
        $this->_update_order_status($order, $payload_decoded);

        status_header(200);
        exit;


	}

	/**
	 * Handle a custom '_whalestack_checkout_id' query var to get orders with the '_whalestack_checkout_id' meta.
	 * @param array $query - Args for WP_Query.
	 * @param array $query_vars - Query vars from WC_Order_Query.
	 * @return array modified $query
	 */
	public function get_order_by_checkout_id($query, $query_vars) {

		if (!empty($query_vars['_whalestack_checkout_id'])) {
			$query['meta_query'][] = array(
				'key' => '_whalestack_checkout_id',
				'value' => esc_attr($query_vars['_whalestack_checkout_id']),
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
	public function _update_order_status($order, $payload) {

        $woo_order_id = $order->get_id();
        $woo_order_state = $order->get_status();
        $whalestack_payload_state = $payload['eventType'];

        switch ($whalestack_payload_state) {

            case 'CHECKOUT_COMPLETED':

                $checkout = $payload['data']['checkout'];
                if (!in_array($woo_order_state, array('completed', 'processing'))) {
                    $payment_details_page = 'https://www.whalestack.com/en/payment/checkout-id/' . $checkout['id'];
                    $order->update_status('processing');
                    $order->add_order_note('<span style="color:#079047">' . sprintf(__('Whalestack payment was successfully processed. Find payment details <a href="%s" target="_blank">here</a>.', 'whalestack'), esc_url($payment_details_page)) . '</span>');
                    $order->payment_complete();
                }
                break;

            case 'CHECKOUT_UNDERPAID':

                $checkout = $payload['data']['checkout'];
                if (!in_array($woo_order_state, array('completed', 'processing'))) {
                    $payment_details_page = 'https://www.whalestack.com/en/unresolved-charge/checkout-id/' . $checkout['id'];
                    $order->update_status('on-hold');
                    $order->add_order_note('<span style="color:#cc292f">' . sprintf(__('Whalestack payment was underpaid by customer. See details and options to resolve it <a href="%s" target="_blank">here</a>.', 'whalestack'), esc_url($payment_details_page)) . '</span>');
                }
                break;

            case 'UNDERPAID_ACCEPTED':

                $checkout = $payload['data']['checkout'];
                if (!in_array($woo_order_state, array('completed', 'processing'))) {
                    $payment_details_page = 'https://www.whalestack.com/en/payment/checkout-id/' . $checkout['id'];
                    $underpaid_accepted_price = $checkout['settlementAmountReceived'] . ' ' . $order->get_currency();
                    $order->update_status('processing');
                    $order->add_order_note('<span style="color:#079047">' . sprintf(__('Underpaid by customer, but payment manually accepted at %1$s and completed. Find payment details <a href="%2$s" target="_blank">here</a>.', 'whalestack'), esc_attr($underpaid_accepted_price), esc_url($payment_details_page)) . '</span>');
                    $order->payment_complete();
                    $order->update_meta_data('_whalestack_underpaid_accepted_price', esc_attr($underpaid_accepted_price));
                }
                break;

            case 'REFUND_COMPLETED':

                $refund = $payload['data']['refund'];
                $context = $payload['data']['refund']['context'];

                if (in_array($context, array('COMPLETED_CHECKOUT', 'UNDERPAID_CHECKOUT'))) {
                    $payment_details_page = 'https://www.whalestack.com/en/refund/' . $refund['id'];
                    $order->update_status('refunded');
                    $order->add_order_note('<span style="color:#007cba;">' . sprintf(__('Order amount was refunded successfully to customer. See details <a href="%s" target="_blank">here</a>.', 'whalestack'), esc_url($payment_details_page)) . '</span>');
                    $order->update_meta_data('_whalestack_refund_id', esc_attr($refund['id']));
                }
                break;

            default:
                WS_Logging_Service::write('Unresolved payload event for order id ' . $woo_order_id . print_r( $payload, true));
        }

        $order->update_meta_data('_whalestack_payment_state', esc_attr($whalestack_payload_state));
        $order->save();

	}

}