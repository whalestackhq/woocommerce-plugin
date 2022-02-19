=== COINQVEST for WooCommerce - Cryptocurrency Payment Gateway ===
Contributors: coinqvest
Tags: woocommerce, crypto, cryptocurrency, payments, payment gateway, payment processing, digital currencies, bitcoin, stellar, lumens, xlm, btc, eth, xrp, ltc, EUR, USD, NGN, BRL
Requires at least: 3.9
Tested up to: 5.9
Stable tag: 1.1
Requires PHP: 5.6
License: Apache 2.0
License URI: https://www.apache.org/licenses/LICENSE-2.0

Accept digital currencies from your clients and settle instantly in your preferred national payout currency (USD, EUR, BRL, NGN) or cryptocurrencies like Bitcoin.

== Description ==

COINQVEST is a [cryptocurrency payment processor](https://www.coinqvest.com) and provides digital currency checkouts that automatically go from Bitcoin to your bank account or crypto wallet. COINQVEST helps online merchants and e-commerce shops programmatically accept and settle payments in new digital currencies while staying compliant, keeping their accountants and tax authorities happy. With COINQVEST, online businesses can denominate and settle sales in a national currency (e.g. EUR, USD, ARS, BRL or NGN) regardless of whether their customers pay in Bitcoin, Ethereum or Stellar Lumens.

The COINQVEST crypto payment gateway supports 45 billing currencies and easily lets you add a crypto payment option to your website or online shop to sell digital content, services, products and much more in your national currency.

= Key features =

* Accepts Bitcoin (BTC), Ethereum (ETH), Ripple (XRP), Stellar Lumens (XLM) and Litecoin (LTC) payments on your WooCommerce shop from customers.
* Instantly settles in your preferred national currency (USD, EUR, ARS, BRL, NGN) or above crypto currencies.
* Sets the product price in your national currency - 45 fiat currencies are available, see full list [here](https://www.coinqvest.com/en/api-docs#get-exchange-rate-global).
* Integrates seemlessly into your WooCommerce website.
* Sets the product price in your national currency.
* Sets the checkout page language in your preferred language.
* Automatically generates invoices.
* Eliminates chargebacks and gives you control over refunds.
* Eliminates currency volatility risks due to instant conversions and settlement.
* Translates the plugin into any required language.
* Includes payment state management for underpaid and completed payments.

= Supported Currencies =

Argentine Peso (ARS), Australian Dollar (AUD), Bahraini Dinar (BHD), Bangladeshi Taka (BDT), Bermudian Dollar (BMD), Bitcoin (BTC), Brazilian Real (BRL), British Pound (GBP), Canadian Dollar (CAD), Chilean Peso (CLP), Chinese Yuan (CNY), Czech Koruna (CZK), Danish Krone (DKK), Emirati Dirham (AED), Ethereum (ETH), Euro (EUR), Hong Kong Dollar (HKD), Hungarian Forint (HUF), Indian Rupee (INR), Indonesian Rupiah (IDR), Israeli Shekel (ILS), Japanese Yen (JPY), Korean Won (KRW), Kuwaiti Dinar (KWD), Litecoin (LTC), Malaysian Ringgit (MYR), Mexican Peso (MXN), Myanmar Kyat (MMK), New Zealand Dollar (NZD), Nigerian Naira (NGN), Norwegian Krone (NOK), Pakistani Rupee (PKR), Philippine Peso (PHP), Polish Zloty (PLN), Ripple (XRP), Russian Ruble (RUB), Saudi Arabian Riyal (SAR), Singapore Dollar (SGD), South African Rand (ZAR), Sri Lankan Rupee (LKR), Stellar (XLM), Swedish Krona (SEK), Swiss Franc (CHF), Taiwan Dollar (TWD), Thai Baht (THB), Turkish Lira (TRY), Ukrainian Hryvnia (UAH), US Dollar (USD), Venezuelan Bolivar (VEF), Vietnamese Dong (VND)

= Use case =

Example: You sell an e-book for 20 USD on your website. Your user pays in Bitcoin and you will receive 20 USD in your bank account. Within minutes. All you need is to implement the COINQVEST payment method into your WooCommerce site.

= Docs and support =

You can find the [plugin guide](https://www.coinqvest.com/en/blog/how-to-accept-cryptocurrency-payments-with-coinqvest-for-woocommerce-c3a2c96c7610), [API documentation](https://www.coinqvest.com/en/api-docs#post-checkout-hosted), [Help Center](https://www.coinqvest.com/en/help-center#overview) and more detailed information about COINQVEST on [coinqvest.com](https://www.coinqvest.com/).

== Installation ==

= Requirements =

* A COINQVEST merchant account -> Sign up [here](http://www.coinqvest.com)

= Plugin installation =

1. Copy the entire `coinqvest-for-woocommerce` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the **Plugins** screen (**Plugins > Installed Plugins**).
1. Go to **WooCommerce > Settings** and click on the **Payments** tab.
1. On the **Payments** tab find COINQVEST and click the **Manage** button

= Plugin configuration =

1. Get your [API key and secret](https://www.coinqvest.com/en/api-settings) from your COINQVEST merchant account.
1. Enter API key and secret into the COINQVEST settings page.
1. Enable COINQVEST payments.
1. Completed payments will automatically update WooCommerce orders to 'processing' (physical goods) or 'completed' (downloadable/virtual items).
1. Manage all payments in your [merchant account](https://www.coinqvest.com). You will be notified by email about every new payment.

== Frequently Asked Questions ==

Do you have questions or issues with COINQVEST? Feel free to contact us anytime!

* [Plugin Guide](https://www.coinqvest.com/en/blog/how-to-accept-cryptocurrency-payments-with-coinqvest-for-woocommerce-c3a2c96c7610)
* [Docs](https://www.coinqvest.com/en/api-docs#post-checkout-hosted)
* [Help Center](https://www.coinqvest.com/en/help-center#overview)

== Screenshots ==

1. Hosted Checkout Page
2. COINQVEST Merchant Dashboard
3. COINQVEST Transaction Records and Invoicing
4. COINQVEST Instant Withdrawals

== Changelog ==

= 1.1.3 =

* Added parameter 'Checkout page display currency'

= 1.1.2 =

* Added 'ORIGIN' as settlement currency option
* Text changes
* Tested for WordPress version 5.9
* Minor fixes

= 1.1.1 =

* Added support for 50 checkout currencies / shop currencies (45 fiat currencies and 5 cryptocurrencies), see full list [here](https://www.coinqvest.com/en/api-docs#get-exchange-rate-global)

= 1.0.10 =

* Improved billing data checks on checkout page

= 1.0.9 =

* Updated payment information display on order detail page

= 1.0.8 =

* Added language selector for checkout page

= 1.0.7 =

* Added cryptocurrencies as settlement currencies
* Added a 'What is COINQVEST?' link in the payment method description

= 1.0.6 =

* Bugfix in the refunds webhook

= 0.0.5 =

* Added payment state handling for underpaid payments, completed payments and refunds

= 0.0.4 =

* Reduced number of requests to COINQVEST API

= 0.0.3 =

* Tested for WordPress 5.5 and WooCommerce 4.3.2

= 0.0.2 =

* Added Brazilian Real (BRL) as settlement currency