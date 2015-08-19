# SDK PHP
SDK PHP for Payname API

[Version française](./README.md)

<!-- markdown-toc start - Don't edit this section. Run M-x markdown-toc/generate-toc again -->
**Table of Contents**

- [Prerequisites](#prerequisites)
- [Installation](#installation)
    - [From sources](#from-sources)
    - [Via Composer](#via-composer)
- [Configuration](#configuration)
- [Examples](#examples)
- [Documentation](#documentation)
- [Tests](#tests)
- [Changelog](#changelog)
    - [Build 4 - 2015 08 11](#build-4---2015-08-11)
    - [Build 3 - 2015 06 22](#build-3---2015-06-22)
    - [Build 2 - 2015 06 22](#build-2---2015-06-22)
    - [Build 1 - 2015 06 19](#build-1---2015-06-19)

<!-- markdown-toc end -->

# Prerequisites

* PHP 5.4 or higher
* Opional: [cURL](http://php.net/manual/en/book.curl.php) installed.

# Installation

## From sources

PHP classes are in the `src/Payname` folder.

Simply download them and copy them in your project, for example in a `vendor/Payname/` folder.

## Via Composer

Composer integration will come "soon".

# Configuration

1. Copy `Payname/Config.class.php.sample` into `Payname/Config.class.php`
2. Simply edit `Payname/Config.class.php` and write your ID and secret (available in your administration panel)

**HINTS:**

* Use the test secret key to switch the SDK to test mode.
* If account is set to "OAuth and simple", SDK will automatically auth on every request.
  Otherwise, use of `Auth::token()` and `Payname::token()` is required.
* By default, the SDK will use standard PHP functions for HTTP calls.
  To use cURL instead, set `Config::USE_CURL` to `true`


# Examples

Integration exemples are available in the `examples/` folder.

They show ways to use User, Popup and other features of Payname API.


# Documentation

PHPDoc documentation is available in `doc/` folder.


# Tests

Requires [phpunit](https://phpunit.de/)

Unit tests are in `tests/` folder.

For now, work is still in progress.


# Changelog

## Build 4 - 2015 08 11

* Added support of payments with card and card tokens
  * Added classes `Payment` and `Card`
* Added example of payment with card: `examples/payment_card.php`
* Added example of payment with token: `examples/payment_token.php`


## Build 3 - 2015 06 22

* Improving popup example with a real popup
* Adding helper methods `User->doc()` and `User->iban()`


## Build 2 - 2015 06 22

* Added support of HTTP call without use of cURL, for environment which have not the extension installed.
* Added an option in `Config` to enable/disable cURL in HTTP calls


## Build 1 - 2015 06 19

* Add main classes Payname, Exception, Config
* Add support of Auth
* Add support of User and dependances (Doc and IBAN) + example
* Add support of Popup creation + example
