<?php
namespace WC_COINQVEST\Inc\Libraries\Api;

defined('ABSPATH') or exit;

/**
 * Class CQ_Logging_Service
 *
 * A logging service
 */
class CQ_Logging_Service {

	/** @var bool Whether or not logging is enabled */
	public static $log_enabled = false;

	/** @var WC_Logger Logger instance */
	public static $log = false;

	/**
	 * Writes to a log file and prepends current time stamp
	 *
	 * @param $message
	 */
	public static function write($message, $level = 'info') {

		if (self::$log_enabled) {
			if (empty( self::$log)) {
				self::$log = wc_get_logger();
			}
			self::$log->log( $level, $message, array('source' => 'coinqvest'));
		}
	}

}