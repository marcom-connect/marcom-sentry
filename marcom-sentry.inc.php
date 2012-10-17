<?php

if (defined('MARCOM_SENTRY_ENABLED') && true === MARCOM_SENTRY_ENABLED) {
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'MarcomSentry.php');
	set_exception_handler('sentry_exception_handler');
	set_error_handler('sentry_error_handler');
}

function sentry_exception_handler($exception){
	MarcomSentry::sendException($exception, sentry_description($exception->getFile(), $exception->getLine()));
	ShowErrorPage($exception);
}

function sentry_error_handler($errno, $errstr = '', $errfile = '', $errline = 0, $errcontext = array()){
	if (false !== $level = sentry_level($errno)) {
		MarcomSentry::sendMessage($errstr, sentry_description($errfile, $errline), $level);
	}
}

function sentry_level($errno){
	switch ($errno) {
		case E_ERROR:
		//case E_WARNING:
			$level = MarcomSentry::ERROR;
			break;
		case E_NOTICE:
		case E_USER_WARNING:
		//case E_STRICT:
			$level = MarcomSentry::INFO;
			break;
		case E_DEPRECATED:
			$level = MarcomSentry::DEBUG;
			break;
		default:
			$level = false;
	}
	return $level;
}

function sentry_description($errfile = '', $errline = 0){
	return sprintf('In %s on line %d', str_replace(INSTALLATION_ROOT, '', $errfile), $errline);
}
