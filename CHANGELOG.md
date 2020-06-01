# Changelog

All notable changes to `laravel-paddle` will be documented in this file

## 1.1.2 - 2020-06-01

- Added a 'GenericWebhook' event, for webhooks with an 'alert_name' key.
- Handles JSON responses that don't have success/response keys.

## 1.1.1 - 2020-05-14

- Better exception handling for responses without a code/message

## 1.1.0 - 2020-03-03

- Support for Laravel 7.0
- Fix for the OrderDetails request URL

## 1.0.6 - 2020-01-28

- Added attribute existance check on the event webhook data

## 1.0.5 - 2019-12-04

- Added `OpenSSL` and `illuminate/validation` dependencies

## 1.0.4 - 2019-12-03

- Refactor

## 1.0.1 + 1.0.2 - 2019-12-02

- Bugfixes

## 1.0.0 - 2019-12-02

- Initial release
