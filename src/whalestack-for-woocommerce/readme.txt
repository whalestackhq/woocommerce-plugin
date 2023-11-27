=== Whalestack for WooCommerce - Bitcoin & Stablecoin (USDC, EURC) Payments Plugin ===
Contributors: whalestack
Tags: woocommerce, crypto, cryptocurrency, stablecoins, USDC, EURC, payments, payment gateway, payment processing, digital currencies, bitcoin, stellar, lumens, xlm, btc,  ltc, EUR, USD, BRL
Requires at least: 3.9
Tested up to: 6.4
Stable tag: 2.0
Requires PHP: 5.6
License: Apache 2.0
License URI: https://www.apache.org/licenses/LICENSE-2.0

Accept Bitcoin, other crypto and stablecoin payments from your customers and instantly settle in your preferred digital currency.

== Description ==

Future-proof your WooCommerce shop for digital payments and checkouts in cryptocurrencies and stablecoins like EURC and USDC.

Enhance your WooCommerce store with Whalestack's advanced cryptocurrency payment options. Integrate Bitcoin, Litecoin, USDC, EURC, and Lightning payments effortlessly. Our solution offers the flexibility to settle transactions in fiat or stablecoins. Experience the convenience of direct checkout integration supporting Bitcoin, USDC, EURC, and Lightning. Our unique bank payout feature ensures smooth conversion of cryptocurrency payments into fiat or stablecoins.

The Whalestack WooCommerce plugin for Bitcoin and stablecoin payments instantly globalizes your business. Benefit from built-in multi-currency support and adaptability to various languages, providing a universally accessible and intuitive shopping experience for customers worldwide.

Elevate your sales by appealing to the rapidly growing demographic of crypto and stablecoin users who favor innovative payment methods. Enhance customer satisfaction and optimize conversion rates with Whalestack's cutting-edge payment solutions, designed to cater to the specific preferences of your buyers.

= Key features =

Enable cryptocurrency (BTC, Lightning, LTC, XLM) and stablecoin (USDC, EURC) payments on your WordPress store for customer transactions.
* Offers immediate settlement in your chosen national currency (USD, EUR, BRL) or the aforementioned cryptocurrencies.
* Enables setting of product prices in your local currency, with a choice of 45 fiat currencies available, detailed list [here](https://www.whalestack.com/en/currencies).
* Effortlessly integrates with your WooCommerce website.
* Allows for the pricing of products in your national currency.
* Customizes the checkout page language to your preference.
* Generates invoices automatically.
* Prevents chargebacks while providing flexibility in managing refunds.
* Reduces risks associated with currency fluctuations through instant conversion and settlement.
* Localizes the plugin in any language as required.
* Manages payment states for underpaid and fully paid transactions.

= Supported Currencies =

Argentine Peso (ARS), Australian Dollar (AUD), Bahraini Dinar (BHD), Bangladeshi Taka (BDT), Bermudian Dollar (BMD), Bitcoin (BTC), Brazilian Real (BRL), British Pound (GBP), Canadian Dollar (CAD), Chilean Peso (CLP), Chinese Yuan (CNY), Czech Koruna (CZK), Danish Krone (DKK), Emirati Dirham (AED), Ethereum (ETH), Euro (EUR), Hong Kong Dollar (HKD), Hungarian Forint (HUF), Indian Rupee (INR), Indonesian Rupiah (IDR), Israeli Shekel (ILS), Japanese Yen (JPY), Korean Won (KRW), Kuwaiti Dinar (KWD), Litecoin (LTC), Malaysian Ringgit (MYR), Mexican Peso (MXN), Myanmar Kyat (MMK), New Zealand Dollar (NZD), Nigerian Naira (NGN), Norwegian Krone (NOK), Pakistani Rupee (PKR), Philippine Peso (PHP), Polish Zloty (PLN), Ripple (XRP), Russian Ruble (RUB), Saudi Arabian Riyal (SAR), Singapore Dollar (SGD), South African Rand (ZAR), Sri Lankan Rupee (LKR), Stellar (XLM), Swedish Krona (SEK), Swiss Franc (CHF), Taiwan Dollar (TWD), Thai Baht (THB), Turkish Lira (TRY), Ukrainian Hryvnia (UAH), US Dollar (USD), Venezuelan Bolivar (VEF), Vietnamese Dong (VND)

= Docs and Support =

* [Plugin Guide](https://www.whalestack.com/en/woocommerce)
* [API Documentation](https://www.whalestack.com/en/api-docs#post-checkout-hosted)
* [Help Center](https://www.whalestack.com/en/help-center#overview)

== Installation ==

= Requirements =

* A Whalestack account -> Sign up [here](http://www.whalestack.com)

= Plugin installation =

1. Copy the entire `whalestack-for-woocommerce` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the **Plugins** screen (**Plugins > Installed Plugins**).
1. Go to **WooCommerce > Settings** and click on the **Payments** tab.
1. On the **Payments** tab find Whalestack and click the **Manage** button

= Plugin configuration =

1. Get your [API key and secret](https://www.whalestack.com/en/api-settings) from your Whalestack merchant account.
1. Enter API key and secret into the Whalestack settings page.
1. Enable Whalestack payments.
1. Completed payments will automatically update WooCommerce orders to 'processing' (physical goods) or 'completed' (downloadable/virtual items).
1. Manage all payments in your [merchant account](https://www.whalestack.com). You will be notified by email about every new payment.

== Screenshots ==

1. Hosted Checkout Page
2. Whalestack Merchant Dashboard
3. Whalestack Transaction Records and Invoicing
4. Whalestack Instant Withdrawals

== Changelog ==

= 2.0.0 =

* Rebranded from COINQVEST to Whalestack

= 1.1.6 =

* Updated settlement currency to reflect new API response from /asset endpoint (asset.id)

= 1.1.5 =

* Added compatibility for high-performace order storage (HPOS)

= 1.1.4 =

* Reduced number of API calls
* Few code optimizations

= 1.1.3 =

* Added parameter 'Checkout page display currency'

= 1.1.2 =

* Added 'ORIGIN' as settlement currency option
* Text changes
* Tested for WordPress version 5.9
* Minor fixes

= 1.1.1 =

* Added support for 50 checkout currencies / shop currencies (45 fiat currencies and 5 cryptocurrencies), see full list [here](https://www.whalestack.com/en/api-docs#get-exchange-rate-global)

= 1.0.10 =

* Improved billing data checks on checkout page

= 1.0.9 =

* Updated payment information display on order detail page

= 1.0.8 =

* Added language selector for checkout page

= 1.0.7 =

* Added cryptocurrencies as settlement currencies
* Added a 'What is Whalestack?' link in the payment method description

= 1.0.6 =

* Bugfix in the refunds webhook

= 0.0.5 =

* Added payment state handling for underpaid payments, completed payments and refunds

= 0.0.4 =

* Reduced number of requests to Whalestack API

= 0.0.3 =

* Tested for WordPress 5.5 and WooCommerce 4.3.2

= 0.0.2 =

* Added Brazilian Real (BRL) as settlement currency