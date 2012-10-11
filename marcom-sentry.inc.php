<?php

if (defined('MARCOM_SENTRY_ENABLED') && true === MARCOM_SENTRY_ENABLED) {
	require_once(dirname(__FILE__) . '/MarcomSentry.php');
	set_exception_handler('sentry_exception_handler');
	set_error_handler('sentry_error_handler_error', E_ERROR);
	set_error_handler('sentry_error_handler_warning', E_WARNING);
	set_error_handler('sentry_error_handler_notice', E_NOTICE);
}

function sentry_exception_handler($exception){
	MarcomSentry::sendException($exception, sprintf('In %s on line %d', str_replace(INSTALLATION_ROOT, '', $exception->getFile()), $exception->getLine()));
	ShowErrorPage($exception);
}

function sentry_error_handler_error($errno, $errstr = '', $errfile = '', $errline = 0, $errcontext = array()){
	sentry_error_handler(MarcomSentry::ERROR, $errno, $errstr, $errfile, $errline, $errcontext);
}
function sentry_error_handler_warning($errno, $errstr = '', $errfile = '', $errline = 0, $errcontext = array()){
	sentry_error_handler(MarcomSentry::WARNING, $errno, $errstr, $errfile, $errline, $errcontext);
}
function sentry_error_handler_notice($errno, $errstr = '', $errfile = '', $errline = 0, $errcontext = array()){
	sentry_error_handler(MarcomSentry::INFO, $errno, $errstr, $errfile, $errline, $errcontext);
}

function sentry_error_handler($level, $errno, $errstr = '', $errfile = '', $errline = 0, $errcontext = array()){
	MarcomSentry::sendMessage($errstr, sprintf('In %s on line %d', str_replace(INSTALLATION_ROOT, '', $errfile), $errline), $level);
}
