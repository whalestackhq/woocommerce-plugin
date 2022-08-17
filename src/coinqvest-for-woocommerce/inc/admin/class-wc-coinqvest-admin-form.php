<?php
namespace WC_COINQVEST\Inc\Admin;
use WC_COINQVEST\Inc\Libraries\Api;

defined('ABSPATH') or exit;

class WC_Coinqvest_Admin_Form {

	public function __construct() {

	}

	public function form_fields($api_key, $api_secret)
    {
        // default
        $settlementAssets = array(
            '0' => 'Select Currency...',
            'ORIGIN' => 'ORIGIN - Settle to the cryptocurrency your client pays with'
        );

        // default
        $languages = array(
            '0' => 'Select language ...',
            'auto' => 'auto - Automatic'
        );

        $parts = parse_url($_SERVER['REQUEST_URI']);
        $client = null;
        $helpers = new WC_Coinqvest_Helpers();

        if (isset($parts['query']) && $parts['query'] == 'page=wc-settings&tab=checkout&section=wc_coinqvest') {

            if (!empty($api_key) && !empty($api_secret)) {

                $client = new Api\CQ_Merchant_Client($api_key, $api_secret, true);

                $assets = $helpers->get_assets($client);
                foreach ($assets as $key => $value) {
                    $settlementAssets[$key] = esc_html($value);
                }

                $langs = $helpers->get_checkout_languages($client);
                foreach ($langs as $key => $value) {
                    $languages[$key] = esc_html($value);
                }
            }

        }

        $form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable COINQVEST Payments', 'coinqvest'),
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Title', 'woocommerce'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                'default' => __('Bitcoin and other cryptocurrencies', 'coinqvest'),
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => __('Description', 'woocommerce'),
                'type' => 'text',
                'desc_tip' => true,
                'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
                'default' => __('Pay with BTC, ETH, XRP, XLM, LTC', 'coinqvest'),
            ),
            'api_key' => array(
                'title' => __('API Key', 'coinqvest'),
                'type' => 'text',
                'description' => sprintf(__('Get your API Key from the COINQVEST Settings page, available %s.', 'coinqvest'), '<a href="https://www.coinqvest.com/en/api-settings?utm_source=woocommerce&utm_medium=' . esc_html($_SERVER['SERVER_NAME']) . '" target="_blank">here</a>'),
            ),
            'api_secret' => array(
                'title' => __('API Secret', 'coinqvest'),
                'type' => 'text',
                'description' => sprintf(__('Get your API Secret from the COINQVEST Settings page, available %s.', 'coinqvest'), '<a href="https://www.coinqvest.com/en/api-settings?utm_source=woocommerce&utm_medium=' . esc_html($_SERVER['SERVER_NAME']) . '" target="_blank">here</a>')
            ),
            'settlement_currency' => array(
                'title' => __('Settlement Currency', 'coinqvest'),
                'type' => 'select',
                'description' => __('The currency that the crypto payments get converted to. If you don\'t choose a currency here, the settlement currency will be the billing currency. Choose ORIGIN if you want to get credited in the exact same currency your customer paid in (without any conversion). API credentials must be provided before currency options show up.', 'coinqvest'),
                'options' => $settlementAssets,
                'default' => 'default',
                'desc_tip' => true,
            )
        );
        $form_fields['checkout_language'] = array(
            'title' => __('Checkout Page Language', 'coinqvest'),
            'type' => 'select',
            'description' => __('The language that your checkout page will display in. Choose \'auto\' to automatically detect the customer\'s main browser language. Fallback language code is \'en\'.', 'coinqvest'),
            'options' => $languages,
            'default' => 'default',
            'desc_tip' => true,
        );
        $form_fields['show_icons'] = array(
            'title' => __('Show logo', 'coinqvest'),
            'type' => 'checkbox',
            'label' => __('Display COINQVEST logo on checkout page.', 'coinqvest'),
            'default' => 'yes',
        );
        $form_fields['debug'] = array(
            'title' => __('Debug log', 'woocommerce'),
            'type' => 'checkbox',
            'label' => __('Enable logging', 'woocommerce'),
            'default' => 'no',
            'description' => sprintf(__('Log COINQVEST API events inside %s', 'coinqvest'), '<code>' . \WC_Log_Handler_File::get_log_file_path('coinqvest') . '</code>'),
        );

		return $form_fields;

	}

}