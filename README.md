# marcom-sentry

The `marcom-sentry` is a PHP library for Marcom applications for Sentry interface.

## Requirements

* PHP >= 5.2
* Sentry instance

## Installation

In the `3rdParty` folder of your Marcom applications.

### Via git clone

```
$ git clone git@github.com:marcom-connect/marcom-sentry.git MarcomSentry
```

### Via git submodule

```
$ git submodule add git@github.com:marcom-connect/marcom-sentry.git MarcomSentry
```

### Via zip archive

[Download](https://github.com/marcom-connect/marcom-sentry/zipball/master) and extract zip archive contents into `MarcomSentry`.

## Configuration

In `www/config.inc.php`

```php
define('MARCOM_SENTRY_ENABLED', true);
define('MARCOM_SENTRY_DSN', 'http://public:secret@sentry.example.com:9000/[PROJECT_ID]');
define('MARCOM_SENTRY_LOGGER', 'custom-logger-name');
require_once('MarcomSentry/marcom-sentry.inc.php');
```

## Usage

```php
// send a message with no description and information level (by default)
MarcomSentry::sendMessage('Message title');

// send a debug message
MarcomSentry::sendMessage('Debug message title', 'Debug message description', MarcomSentry::DEBUG);

// send a warning message
MarcomSentry::sendMessage('Warning message title', 'Warning message description', MarcomSentry::WARNING);

// send an error message
MarcomSentry::sendMessage('Error message title', 'Error message description', MarcomSentry::ERROR);

// send an exception
MarcomSentry::sendException(new Exception('Exception message'), 'Exception description');

// set logger
MarcomSentry::setLogger('new-logger');

// reset logger
MarcomSentry::resetLogger();
```

## Contributors

* Jean Roussel <jean@marcom-connect.com>

### vendor/raven-php

The Raven PHP client was originally written by Michael van Tellingen
and is maintained by the Sentry Team.

http://github.com/getsentry/raven-php/contributors