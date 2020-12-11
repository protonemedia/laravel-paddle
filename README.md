# Laravel Paddle

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-paddle.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-paddle)
![run-tests](https://github.com/protonemedia/laravel-paddle/workflows/run-tests/badge.svg)
[![Quality Score](https://img.shields.io/scrutinizer/g/protonemedia/laravel-paddle.svg?style=flat-square)](https://scrutinizer-ci.com/g/protonemedia/laravel-paddle)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-paddle.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-paddle)

This package provides an integration with [Paddle.com](https://paddle.com) for Laravel. Read the [blogpost](https://protone.media/en/blog/a-new-laravel-package-to-handle-payments-and-subscriptions-with-paddle) about the introduction of the package!

## Features
* Super easy wrapper around the [Paddle.com API](https://developer.paddle.com/api-reference/intro)
* Built-in support for Webhooks and Event handling
* Blade directive to use [Paddle.js](https://paddle.com/docs/paddle-js-overlay-checkout/) in your front-end

## Support

We proudly support the community by developing Laravel packages and giving them away for free. Keeping track of issues and pull requests takes time, but we're happy to help! If this package saves you time or if you're relying on it professionally, please consider [supporting the maintenance and development](https://github.com/sponsors/pascalbaljet).

## Installation

Only the master branch and version 2.0 of this package are compatible with Laravel 8.0. If you're still using an older version of Laravel (or PHP < 7.3), please use the chart below to find out which version you should use. Mind that older versions are no longer supported.

| Laravel Version | Package Version |
|-----------------|-----------------|
| 8.0             | 2.0             |
| 6.0-7.0         | 1.0             |

You can install the package via composer:

```bash
composer require protonemedia/laravel-paddle
```

## Configuration

Publish the config and view files:

```bash
php artisan vendor:publish --provider="ProtoneMedia\LaravelPaddle\PaddleServiceProvider"
```

Set your [Vendor ID and Code](https://vendors.paddle.com/authentication) and the [Public Key](https://vendors.paddle.com/public-key) settings in your `.env` file or in the `config/paddle.php` file. The Public Key is used to verify incoming webhooks from Paddle.

```bash
PADDLE_VENDOR_ID=123
PADDLE_VENDOR_AUTH_CODE=456
PADDLE_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----
...
-----END PUBLIC KEY-----"
```

## Usage

The API calls are available with the `Paddle` facade. Check out the [the documentation](https://developer.paddle.com/api-reference/intro) to learn all about the Paddle API. You can build your API calls fluently or you could simply pass an array which holds the data. This package has some basic validation rules for the given data and this might result in an `InvalidDataException` if your data is invalid. Whenever an API call fails it will throw a `PaddleApiException`.

``` php
// Fluent:
$paddleResponse = Paddle::product()
    ->generatePayLink()
    ->productId($paddlePlanId)
    ->customerEmail($team->owner->email)
    ->passthrough(['team_id' => $team->id])
    ->send();

// Array with payload:
$payload = [
    'product_id' => $paddlePlanId,
    'customer_email' => $team->owner->email,
    'passthrough' => ['team_id' => $team->id],
];

$paddleResponse = Paddle::product()
    ->generatePayLink($payload)
    ->send();

return Redirect::to($paddleResponse['url']);
```

## Available API calls

```php
// alerts
Paddle::alert()->getWebhookHistory();

// checkouts
Paddle::checkout()->getOrderDetails();
Paddle::checkout()->getUserHistory();
Paddle::checkout()->getPrices();

// products
Paddle::product()->listCoupons();
Paddle::product()->createCoupon();
Paddle::product()->updateCoupon();
Paddle::product()->deleteCoupon();

Paddle::product()->listProducts();
Paddle::product()->generateLicense();
Paddle::product()->generatePayLink();
Paddle::product()->listTransactions($entity, $id);

// subscriptions
Paddle::subscription()->listPlans();
Paddle::subscription()->createPlan();

Paddle::subscription()->listUsers();
Paddle::subscription()->updateUser();
Paddle::subscription()->previewUpdate();
Paddle::subscription()->cancelUser();

Paddle::subscription()->listModifiers();
Paddle::subscription()->createModifier();
Paddle::subscription()->deleteModifier();

Paddle::subscription()->listPayments();
Paddle::subscription()->reschedulePayment();
Paddle::subscription()->createOneOffCharge($subscriptionId);
```

## Webhooks and Laravel Events

You can configure your webhook URI in the `paddle.php` config file. Update your [webhook settings](https://vendors.paddle.com/alerts-webhooks) at Paddle accordingly. By default the URI is `paddle/webhook`. This means that the webhook calls will be posted to `https://your-domain.com/paddle/webhook`.

Every webhook will be mapped to an Event and contains the payload of the webhook. For example when the [Subscription Created](https://developer.paddle.com/webhook-reference/subscription-alerts/subscription-created) webhook is called, the request is verified and a `SubscriptionCreated` event will be fired.

Events:
* `ProtoneMedia\LaravelPaddle\Events\HighRiskTransactionCreated`
* `ProtoneMedia\LaravelPaddle\Events\HighRiskTransactionUpdated`
* `ProtoneMedia\LaravelPaddle\Events\LockerProcessed`
* `ProtoneMedia\LaravelPaddle\Events\NewAudienceMember`
* `ProtoneMedia\LaravelPaddle\Events\PaymentDisputeClosed`
* `ProtoneMedia\LaravelPaddle\Events\PaymentDisputeCreated`
* `ProtoneMedia\LaravelPaddle\Events\PaymentRefunded`
* `ProtoneMedia\LaravelPaddle\Events\PaymentSucceeded`
* `ProtoneMedia\LaravelPaddle\Events\SubscriptionCancelled`
* `ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated`
* `ProtoneMedia\LaravelPaddle\Events\SubscriptionPaymentFailed`
* `ProtoneMedia\LaravelPaddle\Events\SubscriptionPaymentRefunded`
* `ProtoneMedia\LaravelPaddle\Events\SubscriptionPaymentSucceeded`
* `ProtoneMedia\LaravelPaddle\Events\SubscriptionUpdated`
* `ProtoneMedia\LaravelPaddle\Events\TransferCreated`
* `ProtoneMedia\LaravelPaddle\Events\TransferPaid`
* `ProtoneMedia\LaravelPaddle\Events\UpdateAudienceMember`

Some webhooks, like the [Fulfillment Webhook](https://developer.paddle.com/webhook-reference/product-fulfillment/fulfillment-webhook), don't have an `alert_name` key. Those webhooks will be mapped to a `ProtoneMedia\LaravelPaddle\Events\GenericWebhook` event.

When you register a listener to handle the event, the payload is easily accessible. You also have access to the original HTTP request.

```php
<?php

namespace App\Listeners;

use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;

class CreateSubscriptionModel
{
    public function handle(SubscriptionCreated $event)
    {
        $status = $event->status;

        $nextBillDate = $event->next_bill_date;

        // or

        $webhookData = $event->all();

        //

        $request = $event->getRequest();
    }
}
```

## Blade directive

This directive imports the Paddle JavaScript library and configures it with your Vendor ID.

```php
<body>
    {{-- your app --}}

    @paddle
</body>
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Other Laravel packages

* [`Laravel Analytics Event Tracking`](https://github.com/protonemedia/laravel-analytics-event-tracking): Laravel package to easily send events to Google Analytics.
* [`Laravel Blade On Demand`](https://github.com/protonemedia/laravel-blade-on-demand): Laravel package to compile Blade templates in memory.
* [`Laravel Cross Eloquent Search`](https://github.com/protonemedia/laravel-cross-eloquent-search): Laravel package to search through multiple Eloquent models.
* [`Laravel Eloquent Scope as Select`](https://github.com/protonemedia/laravel-eloquent-scope-as-select): Stop duplicating your Eloquent query scopes and constraints in PHP. This package lets you re-use your query scopes and constraints by adding them as a subquery.
* [`Laravel Eloquent Where Not`](https://github.com/protonemedia/laravel-eloquent-where-not): This Laravel package allows you to flip/invert an Eloquent scope, or really any query constraint.
* [`Laravel FFMpeg`](https://github.com/protonemedia/laravel-ffmpeg): This package provides an integration with FFmpeg for Laravel. The storage of the files is handled by Laravel's Filesystem.
* [`Laravel Form Components`](https://github.com/protonemedia/laravel-form-components): Blade components to rapidly build forms with Tailwind CSS Custom Forms and Bootstrap 4. Supports validation, model binding, default values, translations, includes default vendor styling and fully customizable!
* [`Laravel Mixins`](https://github.com/protonemedia/laravel-mixins): A collection of Laravel goodies.
* [`Laravel Verify New Email`](https://github.com/protonemedia/laravel-verify-new-email): This package adds support for verifying new email addresses: when a user updates its email address, it won't replace the old one until the new one is verified.
* [`Laravel WebDAV`](https://github.com/protonemedia/laravel-webdav): WebDAV driver for Laravel's Filesystem.

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/protonemedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
