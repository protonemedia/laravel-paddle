# Laravel Paddle Reference

Complete reference for `protonemedia/laravel-paddle.`. Full documentation: https://github.com/protonemedia/laravel-paddle#readme

Complete reference for `protonemedia/laravel-paddle` (Paddle.com API integration for Laravel).

Primary docs: https://github.com/protonemedia/laravel-paddle#readme

## What this package provides

- A fluent API wrapper around the Paddle Vendor API via the `Paddle` facade.
- Webhook endpoint handling + signature verification via Paddle public key.
- A set of Laravel Events mapped from webhooks (e.g. `SubscriptionCreated`).
- A Blade directive (`@paddle`) to include/configure Paddle.js.
- Optional support for the Paddle Sandbox environment.

> Note from the upstream README: the maintainer warns about the future of the package and suggests considering Laravel Cashier (Paddle). Do not “fix” or remove that notice in documentation.

## Installation

```bash
composer require protonemedia/laravel-paddle
```

Laravel compatibility is version-dependent (see the upstream README table).

## Configuration

### Publish config

```bash
php artisan vendor:publish --provider="ProtoneMedia\LaravelPaddle\PaddleServiceProvider" --tag=config
```

### Environment variables

Set vendor credentials and the webhook verification public key (either in `.env` or `config/paddle.php`):

```bash
PADDLE_SANDBOX=false
PADDLE_VENDOR_ID=123
PADDLE_VENDOR_AUTH_CODE=456
PADDLE_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----
...
-----END PUBLIC KEY-----"
```

### Paddle Sandbox

- Enable sandbox via the `sandbox_environment` config key.
- Sandbox affects both API URLs and the Paddle JS library used by the Blade directive.
- If you previously published views, republish them after enabling sandbox:

```bash
php artisan vendor:publish --provider="ProtoneMedia\LaravelPaddle\PaddleServiceProvider" --tag=views
```

## Using the Paddle facade

The API is available via `Paddle::...()` and can be used fluently or by passing an array payload.

### Fluent payload builder

```php
use ProtoneMedia\LaravelPaddle\Paddle;

$paddleResponse = Paddle::product()
    ->generatePayLink()
    ->productId($paddlePlanId)
    ->customerEmail($team->owner->email)
    ->passthrough(['team_id' => $team->id])
    ->send();

return redirect()->to($paddleResponse['url']);
```

### Array payload

```php
$payload = [
    'product_id' => $paddlePlanId,
    'customer_email' => $team->owner->email,
    'passthrough' => ['team_id' => $team->id],
];

$paddleResponse = Paddle::product()
    ->generatePayLink($payload)
    ->send();
```

### Error handling / exceptions

From the README:

- If validation fails, the package may throw an `InvalidDataException`.
- If the API call fails, it throws a `PaddleApiException`.

When adding new API call builders or adjusting payload validation, keep these expectations stable.

## Available API call groups (from README)

### Alerts

```php
Paddle::alert()->getWebhookHistory();
```

### Checkouts

```php
Paddle::checkout()->getOrderDetails();
Paddle::checkout()->getUserHistory();
Paddle::checkout()->getPrices();
```

### Products

```php
Paddle::product()->listCoupons();
Paddle::product()->createCoupon();
Paddle::product()->updateCoupon();
Paddle::product()->deleteCoupon();

Paddle::product()->listProducts();
Paddle::product()->generateLicense();
Paddle::product()->generatePayLink();
Paddle::product()->listTransactions($entity, $id);
```

### Subscriptions

```php
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

### Endpoint

- The webhook URI is configurable in `config/paddle.php`.
- By default it is `paddle/webhook`, so Paddle should POST to:
  `https://your-domain.com/paddle/webhook`

### Event mapping

Each webhook is mapped to a Laravel Event class and exposes the webhook payload.
Examples from the README event list include:

- `ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated`
- `ProtoneMedia\LaravelPaddle\Events\SubscriptionUpdated`
- `ProtoneMedia\LaravelPaddle\Events\PaymentSucceeded`
- …and more.

Some webhooks (e.g. fulfillment) do not include `alert_name`; those are mapped to:

- `ProtoneMedia\LaravelPaddle\Events\GenericWebhook`

### Listening to events

```php
namespace App\Listeners;

use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;

class CreateSubscriptionModel
{
    public function handle(SubscriptionCreated $event): void
    {
        $status = $event->status;
        $nextBillDate = $event->next_bill_date;

        $all = $event->all();
        $request = $event->getRequest();
    }
}
```

### Common pitfalls

- **Public key formatting:** the public key must include the header/footer lines and newlines.
- **Sandbox vs production:** ensure both API and JS environments match (don’t mix production JS with sandbox API).
- **Webhook authenticity:** do not weaken verification logic; treat `PADDLE_PUBLIC_KEY` as required for production.
- **Generic webhooks:** not every webhook has `alert_name`; code that assumes it will break.

## Blade directive (`@paddle`)

The directive imports Paddle.js and configures it with your Vendor ID:

```blade
<body>
    {{-- your app --}}

    @paddle
</body>
```

When adjusting the directive/view, keep output stable and do not introduce breaking DOM/JS changes.

## Patterns and conventions

- Prefer showing usage via the `Paddle` facade; keep the fluent “builder” style consistent.
- Payloads are typically validated before sending; keep validation errors actionable.
- Events should provide both convenient accessors (via magic properties) and access to the raw request.
