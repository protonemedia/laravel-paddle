---
name: laravel-paddle-development
description: Build and work with protonemedia/laravel-paddle features including Paddle API calls, webhook handling, subscription management, pay link generation, and Blade integration.
license: MIT
metadata:
  author: ProtoneMedia
---

# Laravel Paddle Development

## Overview
Use protonemedia/laravel-paddle to integrate the Paddle.com billing API with Laravel. Supports fluent API calls via the `Paddle` facade, webhook event dispatching with signature verification, and a `@paddle` Blade directive for Paddle.js.

## When to Activate
- Activate when working with Paddle billing integration, API calls, or webhook handling in Laravel.
- Activate when code references the `Paddle` facade, Paddle event classes (e.g. `SubscriptionCreated`), or `config/paddle.php`.
- Activate when the user wants to generate pay links, manage subscriptions, handle Paddle webhooks, or configure Paddle.js.

## Scope
- In scope: documented public API usage, configuration, webhook events, Blade directive, testing patterns, and common integration recipes.
- Out of scope: modifying this package's internal source code unless the user explicitly says they are contributing to the package.

## Workflow
1. Identify the task (install/setup, configuration, API calls, webhooks, Blade, tests, etc.).
2. Read `references/laravel-paddle-guide.md` and focus on the relevant section.
3. Apply the documented patterns, keeping code minimal and Laravel-native.

## Core Concepts

### Paddle Facade (Fluent API)
```php
use ProtoneMedia\LaravelPaddle\Paddle;

$response = Paddle::product()
    ->generatePayLink()
    ->productId($planId)
    ->customerEmail($user->email)
    ->passthrough(['team_id' => $team->id])
    ->send();
```

### Webhook Events
```php
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;

class HandleSubscription
{
    public function handle(SubscriptionCreated $event): void
    {
        $status = $event->status;
        $all = $event->all();
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
- Use the `Paddle` facade with the fluent builder style for API calls.
- Include the full PEM header/footer and newlines when setting `PADDLE_PUBLIC_KEY`.
- Match sandbox settings across both API and Paddle.js (don't mix environments).
- Use Laravel event listeners to handle webhook payloads.
- Call `->send()` to execute the API request.

Don't:
- Don't invent undocumented methods or options; stick to the docs and reference.
- Don't assume every webhook includes `alert_name`; some (e.g. fulfillment) use `GenericWebhook`.
- Don't weaken webhook signature verification; treat `PADDLE_PUBLIC_KEY` as required for production.
- Don't suggest changing package internals unless the user explicitly wants to contribute upstream.

## References
- `references/laravel-paddle-guide.md`
