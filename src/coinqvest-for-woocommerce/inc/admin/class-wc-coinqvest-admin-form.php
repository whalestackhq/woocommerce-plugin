<?php
namespace WC_COINQVEST\Inc\Admin;
use WC_COINQVEST\Inc\Libraries\Api;

defined( 'ABSPATH' ) or exit;

class WC_Coinqvest_Admin_Form {


	public function __construct() {

	}

	public function form_fields($api_key, $api_secret) {

		$fiat_currencies = array(
			'0' => 'Select currency ...'
		);

		if (!empty($api_key) && !empty($api_secret)) {

			$client = new Api\CQ_Merchant_Client(
				$api_key,
				$api_secret,
				true
			);

			$response = $client->get('/fiat-currencies');

			if ($response->httpStatusCode == 200) {

				$fiats = json_decode($response->responseBody);

				foreach ($fiats->fiatCurrencies as $currency) {

					$fiat_currencies[$currency->assetCode] = esc_html($currency->assetCode) . ' - ' . esc_html($currency->assetName);

				}
			}
		}

		$form_fields = array(
			'enabled'        => array(
				'title'   => __('Enable/Disable', 'woocommerce'),
				'type'    => 'checkbox',
				'label'   => __('Enable COINQVEST Payments', 'coinqvest'),
				'default' => 'yes',
			),
			'title'          => array(
				'title'       => __('Title', 'woocommerce'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default'     => __('Bitcoin and other cryptocurrencies', 'coinqvest'),
				'desc_tip'    => true,
			),
			'description'    => array(
				'title'       => __('Description', 'woocommerce'),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
				'default'     => __('Pay with BTC, ETH, XRP, XLM, LTC', 'coinqvest'),
			),
			'api_key'        => array(
				'title'       => __('API Key', 'coinqvest'),
				'type'        => 'text',
				'description' => sprintf(__('Get your API Key from the COINQVEST Settings page, available %s.', 'coinqvest'), '<a href="https://www.coinqvest.com/en/api-settings" target="_blank">here</a>'),
			),
			'api_secret'        => array(
				'title'       => __('API Secret', 'coinqvest'),
				'type'        => 'text',
				'description' => sprintf(__('Get your API Secret from the COINQVEST Settings page, available %s.', 'coinqvest'), '<a href="https://www.coinqvest.com/en/api-settings" target="_blank">here</a>')
			),
			'settlement_currency' => array(
				'title'       => __('Settlement Currency', 'coinqvest'),
				'type'        => 'select',
				'description' => __('The currency that the crypto payments get converted to. If you don\'t choose a currency here, the settlement currency will be the billing currency. API credentials must be provided before currency options show up.', 'coinqvest'),
				'options'     => $fiat_currencies,
				'default' => 'default',
				'desc_tip'    => true,
			),
			'show_icons'     => array(
				'title'       => __('Show icons', 'coinqvest'),
				'type'        => 'checkbox',
				'label'       => __('Display currency icons on checkout page.', 'coinqvest'),
				'default'     => 'yes',
			),
			'debug'          => array(
				'title'       => __('Debug log', 'woocommerce'),
				'type'        => 'checkbox',
				'label'       => __('Enable logging', 'woocommerce'),
				'default'     => 'no',
				'description' => sprintf( __('Log COINQVEST API events inside %s', 'coinqvest'), '<code>' . \WC_Log_Handler_File::get_log_file_path('coinqvest') . '</code>'),
			)

		);

		return $form_fields;

	}

}