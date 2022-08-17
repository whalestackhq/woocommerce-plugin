<?php
namespace WC_COINQVEST\Inc\Admin;

defined('ABSPATH') or exit;

class WC_Coinqvest_Helpers {

	public function __construct() {

	}

    public function get_assets($client) {

	    $assets = array();
        $response = $client->get('/assets');
        if ($response->httpStatusCode == 200) {
            $items = json_decode($response->responseBody);
            foreach ($items->assets as $asset) {
                $assets[$asset->assetCode] = $asset->assetCode . ' - ' . $asset->name;
            }
        }
        return $assets;

    }

    public function get_checkout_languages($client) {

	    $languages = array();
        $response = $client->get('/languages');
        if ($response->httpStatusCode == 200) {
            $langs = json_decode($response->responseBody);
            foreach ($langs->languages as $lang) {
                $languages[$lang->languageCode] = esc_html($lang->languageCode) . ' - ' . esc_html($lang->name);
            }
        }
        return $languages;

    }

}