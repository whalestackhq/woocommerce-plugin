<?php
namespace WC_COINQVEST\Inc\Admin;

defined('ABSPATH') or exit;

class WC_Coinqvest_Helpers {

	public function __construct() {

	}

    public function get_fiat_currencies($client) {

        $currencies = array();
        $response = $client->get('/fiat-currencies');
        if ($response->httpStatusCode == 200) {
            $fiats = json_decode($response->responseBody);
            foreach ($fiats->fiatCurrencies as $currency) {
                $currencies[$currency->assetCode] = $currency->assetCode . ' - ' .$currency->assetName;
            }

        }
        return $currencies;

    }

    public function get_blockchain_currencies($client) {

        $currencies = array();
        $response = $client->get('/blockchains');
        if ($response->httpStatusCode == 200) {
            $chains = json_decode($response->responseBody);
            foreach ($chains->blockchains as $blockchain) {
                $currencies[$blockchain->nativeAssetCode] = $blockchain->nativeAssetCode . ' - ' . $blockchain->nativeAssetName;
            }
        }
        return $currencies;

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

    public function overrideCheckoutValues($checkout, $exchangeRate, $displayCurrency) {

        $checkout['charge']['currency'] = ($checkout['settlementCurrency'] == 'ORIGIN') ? $displayCurrency : $checkout['settlementCurrency'];

        if (isset($checkout['charge']['lineItems']) && !empty($checkout['charge']['lineItems'])) {
            foreach ($checkout['charge']['lineItems'] as $key => $item) {
                $checkout['charge']['lineItems'][$key]['netAmount'] = self::numberFormat($item['netAmount'] / $exchangeRate, 7);
            }
        }
        if (isset($checkout['charge']['discountItems']) && !empty($checkout['charge']['discountItems'])) {
            foreach ($checkout['charge']['discountItems'] as $key => $item) {
                $checkout['charge']['discountItems'][$key]['netAmount'] = self::numberFormat($item['netAmount'] / $exchangeRate, 7);
            }
        }
        if (isset($checkout['charge']['shippingCostItems']) && !empty($checkout['charge']['shippingCostItems'])) {
            foreach ($checkout['charge']['shippingCostItems'] as $key => $item) {
                $checkout['charge']['shippingCostItems'][$key]['netAmount'] = self::numberFormat($item['netAmount'] / $exchangeRate, 7);
            }
        }

        return $checkout;

    }

    public static function numberFormat($number, $decimals) {
        return number_format($number, $decimals, '.', '');
    }

    public function isFiat($client, $assetCode) {

        $isFiat = false;
        $response = $client->get('/fiat-currencies');
        $response = json_decode($response->responseBody);
        if (isset($response->fiatCurrencies)) {
            foreach ($response->fiatCurrencies as $fiat) {
                if ($fiat->assetCode == $assetCode) {
                    $isFiat = true;
                }
            }
        }
        return $isFiat;

    }

    public function isBlockchain($client, $assetCode) {

        $isBlockchain = false;
        $response = $client->get('/blockchains');
        $response = json_decode($response->responseBody);
        if (isset($response->blockchains)) {
            foreach ($response->blockchains as $blockchain) {
                if ($blockchain->nativeAssetCode == $assetCode) {
                    $isBlockchain = true;
                }
            }
        }
        return $isBlockchain;

    }

    public function getSupportedCurrencies($client) {

        $currencies = array();

        if (is_null($client)) {
            return $currencies;
        }

        $response = $client->get('/fiat-currencies');
        $fiat_currencies = json_decode($response->responseBody);

        if ($response->httpStatusCode == 200)
        {
            foreach ($fiat_currencies->fiatCurrencies as $currency)
            {
                array_push($currencies, $currency->assetCode);
            }
        }

        $response = $client->get('/blockchains');
        $chains = json_decode($response->responseBody);

        if ($response->httpStatusCode == 200)
        {
            foreach ($chains->blockchains as $blockchain)
            {
                array_push($currencies, $blockchain->nativeAssetCode);
            }
        }

        return $currencies;

    }

    public function isSupportedCurrency($client, $currencyCode) {

        if (is_null($client)) {
            return false;
        }

        if (is_null($currencyCode) || empty($currencyCode) || strlen($currencyCode) != 3) {
            return false;
        }

        $supportedCurrencies = $this->getSupportedCurrencies($client);

        if (in_array($currencyCode, $supportedCurrencies)) {
            return true;
        }

        return false;

    }

}