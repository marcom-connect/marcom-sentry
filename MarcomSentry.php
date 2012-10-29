<?php

$baseDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Raven' . DIRECTORY_SEPARATOR;
require_once($baseDir . 'Client.php');
require_once($baseDir . 'Compat.php');
require_once($baseDir . 'ErrorHandler.php');
require_once($baseDir . 'Processor.php');
require_once($baseDir . 'SanitizeDataProcessor.php');
require_once($baseDir . 'Serializer.php');
require_once($baseDir . 'Stacktrace.php');
require_once($baseDir . 'Util.php');

/**
 * MarcomSentry allows you to send message and exception to Sentry.
 * 
 * @author Jean Roussel <jean@marcom-connect.com>
 * @copyright Marcom Connect
 *
 */
class MarcomSentry extends Raven_Client {

	static protected
		$_instance = null,
		$_logger = null;

	/**
	* Retrieves the singleton instance of this class.
	*
	* @return MarcomSentry A MarcomSentry implementation instance.
	*/
	static public function getInstance(){
		if (!isset(self::$_instance)) {
			if (!defined('MARCOM_SENTRY_DSN')) {
				throw new Exception('Please define "MARCOM_SENTRY_DSN" constant.');
			}
			self::$_instance = new MarcomSentry(MARCOM_SENTRY_DSN);
		}
		return self::$_instance;
	}

	/**
	* Send a message to Sentry.
	*
	* @param string $title Message title
	* @param string $description Message description
	* @param string $level Message level
	*
	* @return integer Sentry event ID 
	*/
	static public function sendMessage($title, $description = '', $level = self::INFO){
		return self::getInstance()->captureMessage($title, array('description' => $description), $level);
	}

	/**
	* Send an exception to Sentry.
	*
	* @param Exception $exception Exception
	* @param string $description Exception description
	*
	* @return integer Sentry event ID 
	*/
	static public function sendException($exception, $description = ''){
		return self::getInstance()->captureException($exception, $description);
	}

	/**
    * Log a message to sentry
    */
	public function capture($data, $stack){
		if (!empty($data['sentry.interfaces.Message']['params']['description'])) {
			$data['culprit'] = $data['message'];
			$data['message'] = $data['sentry.interfaces.Message']['params']['description'];
			unset($data['sentry.interfaces.Message']['params']['description']);
		}
		if (!empty($data['sentry.interfaces.Exception']['value'])) {
			$data['message'] = !empty($data['culprit'])?$data['culprit']:' ';
			$data['culprit'] = $data['sentry.interfaces.Exception']['value'];
		}
		if (!isset($data['logger'])) {
			if (null !== self::$_logger) {
				$data['logger'] = self::$_logger;
			} elseif (defined('MARCOM_SENTRY_LOGGER')) {
				$data['logger'] = MARCOM_SENTRY_LOGGER;
			} elseif (defined('APPLICATION_VERSION_INSTALLATION_FULLNAME')) {
				$data['logger'] = APPLICATION_VERSION_INSTALLATION_FULLNAME;
			} elseif (defined('APPLICATION_VERSION_INSTALLATION')) {
				$data['logger'] = APPLICATION_VERSION_INSTALLATION;
			} else {
				$data['logger'] = 'eschedule-pro';
			}
		}
		return parent::capture($data, $stack);
	}

	/**
	* Set Sentry logger.
	*
	* @param string $logger Logger
	*/
	static public function setLogger($logger){
		self::$_logger = $logger;
	}
	/**
	* Reset Sentry logger.
	*/
	static public function resetLogger(){
		self::$_logger = null;
	}

}