---
name: paddle-development
description: Build and work with protonemedia/laravel-paddle features including generating pay links, managing subscriptions, handling webhook events, and integrating Paddle.js via Blade directives.
license: MIT
metadata:
  author: Protone Media
---

# Paddle Development

## Overview
Use protonemedia/laravel-paddle to integrate Paddle.com payments and subscriptions into Laravel. Supports fluent API calls, webhook signature verification, event-driven webhook handling, and a `@paddle` Blade directive for Paddle.js.

## When to Activate
- Activate when working with Paddle payments, subscriptions, or checkout flows in Laravel.
- Activate when code references the `Paddle` facade, `PaddleApiException`, or any `ProtoneMedia\LaravelPaddle\Events` class.
- Activate when the user wants to generate pay links, manage subscription plans, handle Paddle webhooks, or configure Paddle credentials.

## Scope
- In scope: API calls (products, subscriptions, checkout, alerts), webhook events, pay link generation, Blade directive setup, configuration.
- Out of scope: direct Paddle REST API usage without this package, Laravel Cashier (Paddle), non-Laravel frameworks.

## Workflow
1. Identify the task (API call, webhook handling, Blade setup, configuration, etc.).
2. Read `references/paddle-guide.md` and focus on the relevant section.
3. Apply the patterns from the reference, keeping code minimal and Laravel-native.

## Core Concepts

### Configuration
Set credentials in `.env` and publish the config:

```php
// .env
PADDLE_VENDOR_ID=123
PADDLE_VENDOR_AUTH_CODE=456
PADDLE_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----\n...\n-----END PUBLIC KEY-----"
PADDLE_SANDBOX=false
```

### Generating a Pay Link
```php
use ProtoneMedia\LaravelPaddle\Paddle;

$response = Paddle::product()
    ->generatePayLink()
    ->productId($planId)
    ->customerEmail($user->email)
    ->passthrough(['user_id' => $user->id])
    ->send();

$payLink = $response['url'];
```

### Listing Subscription Plans
```php
$plans = Paddle::subscription()
    ->listPlans()
    ->send();
```

### Handling Webhook Events
```php
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;

class HandleSubscriptionCreated
{
    public function handle(SubscriptionCreated $event)
    {
        $status = $event->status;
        $passthrough = $event->passthrough; // auto-decoded JSON
        $allData = $event->all();
    }
}
```

### Blade Directive
```blade
<body>
    {{-- your app --}}

    @paddle
</body>
```

## Do and Don't

Do:
- Always call `->send()` to execute API requests.
- Use the fluent interface or pass an array to API methods — both are supported.
- Use `passthrough` to attach custom metadata (e.g., user ID) to payments; it is auto-encoded to JSON.
- Register webhook event listeners in your `EventServiceProvider` to handle Paddle notifications.
- Set `PADDLE_SANDBOX=true` in `.env` when developing against the Paddle Sandbox.
- Use the `@paddle` Blade directive to include and configure Paddle.js.

Don't:
- Don't forget to set `PADDLE_PUBLIC_KEY` — webhook signature verification will fail without it.
- Don't use `env()` in application code for Paddle credentials; rely on `config('paddle.*')`.
- Don't manually build Paddle API URLs; use the `Paddle` facade methods instead.
- Don't skip webhook signature verification in production — the package handles this automatically.

## References
- `references/paddle-guide.md`
