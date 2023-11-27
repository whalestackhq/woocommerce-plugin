<?php
namespace WC_Whalestack\Inc\Admin;
use WC_Whalestack\Inc\Libraries\Api;

defined('ABSPATH') or exit;

class WC_Whalestack_Admin_Form {

	public function __construct() {

	}

	public function form_fields($api_key, $api_secret)
    {
        // default
        $settlementAssets = array(
            '0' => 'Select asset...',
            'ORIGIN' => 'ORIGIN - Settle to the cryptocurrency your client pays with'
        );

        // default
        $languages = array(
            '0' => 'Select language ...',
            'auto' => 'auto - Automatic'
        );

        $parts = parse_url($_SERVER['REQUEST_URI']);
        $client = null;
        $helpers = new WC_Whalestack_Helpers();

        if (isset($parts['query']) && $parts['query'] == 'page=wc-settings&tab=checkout&section=wc_whalestack') {

            if (!empty($api_key) && !empty($api_secret)) {

                $client = new Api\WS_Merchant_Client($api_key, $api_secret, true);

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
                'label' => __('Enable Whalestack Payments', 'whalestack'),
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Title', 'woocommerce'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                'default' => __('Bitcoin and Stablecoins', 'whalestack'),
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => __('Description', 'woocommerce'),
                'type' => 'text',
                'desc_tip' => true,
                'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
                'default' => __('Pay with BTC, LTC, Lightning, XLM, USDC, EURC via Whalestack.com', 'whalestack'),
            ),
            'api_key' => array(
                'title' => __('API Key', 'whalestack'),
                'type' => 'text',
                'description' => sprintf(__('Get your API Key from the Whalestack Settings page, available %s.', 'whalestack'), '<a href="https://www.whalestack.com/en/api-settings" target="_blank">here</a>'),
            ),
            'api_secret' => array(
                'title' => __('API Secret', 'whalestack'),
                'type' => 'text',
                'description' => sprintf(__('Get your API Secret from the Whalestack Settings page, available %s.', 'whalestack'), '<a href="https://www.whalestack.com/en/api-settings" target="_blank">here</a>')
            ),
            'settlement_currency' => array(
                'title' => __('Settlement Asset', 'whalestack'),
                'type' => 'select',
                'description' => __('The currency/asset that the payments get converted to. If you don\'t choose an asset, the settlement asset will be the payment currency. Choose ORIGIN if you want to get credited in the exact same currency your customer paid in (without any conversion). API credentials must be provided before currency options show up.', 'whalestack'),
                'options' => $settlementAssets,
                'default' => 'default',
                'desc_tip' => true,
            )
        );
        $form_fields['checkout_language'] = array(
            'title' => __('Checkout Page Language', 'whalestack'),
            'type' => 'select',
            'description' => __('The language that your checkout page will display in. Choose \'auto\' to automatically detect the customer\'s main browser language. Fallback language code is \'en\'.', 'whalestack'),
            'options' => $languages,
            'default' => 'default',
            'desc_tip' => true,
        );
        $form_fields['show_icons'] = array(
            'title' => __('Show logo', 'whalestack'),
            'type' => 'checkbox',
            'label' => __('Display Whalestack logo on checkout page.', 'whalestack'),
            'default' => 'yes',
        );
        $form_fields['debug'] = array(
            'title' => __('Debug log', 'woocommerce'),
            'type' => 'checkbox',
            'label' => __('Enable logging', 'woocommerce'),
            'default' => 'no',
            'description' => sprintf(__('Log Whalestack API events inside %s', 'whalestack'), '<code>' . \WC_Log_Handler_File::get_log_file_path('whalestack') . '</code>'),
        );

		return $form_fields;

	}

}