# Laravel Paddle Reference

Complete reference for `protonemedia/laravel-paddle`. Repository: https://github.com/protonemedia/laravel-paddle

## Installation & Configuration

Publish the config file:

```bash
php artisan vendor:publish --provider="ProtoneMedia\LaravelPaddle\PaddleServiceProvider"
```

Set environment variables:

```
PADDLE_VENDOR_ID=123
PADDLE_VENDOR_AUTH_CODE=456
PADDLE_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----
...
-----END PUBLIC KEY-----"
PADDLE_SANDBOX=false
```

Key `config/paddle.php` options:

```php
return [
    'vendor_id'           => env('PADDLE_VENDOR_ID'),
    'vendor_auth_code'    => env('PADDLE_VENDOR_AUTH_CODE'),
    'public_key'          => env('PADDLE_PUBLIC_KEY'),
    'webhook_uri'         => 'paddle/webhook',
    'sandbox_environment' => env('PADDLE_SANDBOX', false),
];
```

## Paddle Facade

The `Paddle` facade is the entry point for all API calls:

```php
use ProtoneMedia\LaravelPaddle\Paddle;

Paddle::product()      // Product API
Paddle::checkout()     // Checkout API
Paddle::subscription() // Subscription API
Paddle::alert()        // Alert API
```

## Product API

### Generate a Pay Link

```php
// Fluent style
$response = Paddle::product()
    ->generatePayLink()
    ->productId($planId)
    ->customerEmail('user@example.com')
    ->passthrough(['user_id' => $user->id])
    ->send();

$payLink = $response['url'];

// Array style
$response = Paddle::product()
    ->generatePayLink([
        'product_id'     => $planId,
        'customer_email' => 'user@example.com',
        'passthrough'    => ['user_id' => $user->id],
    ])
    ->send();
```

### List Products

```php
$products = Paddle::product()
    ->listProducts()
    ->send();
```

### List Transactions

```php
$transactions = Paddle::product()
    ->listTransactions('user', $userId)
    ->send();
```

### Generate License

```php
$license = Paddle::product()
    ->generateLicense([
        'product_id'    => $productId,
        'allowed_uses'  => 5,
    ])
    ->send();
```

## Subscription API

### List Plans

```php
$plans = Paddle::subscription()
    ->listPlans()
    ->send();
```

### Create Plan

```php
$plan = Paddle::subscription()
    ->createPlan([
        'plan_name'    => 'Pro Monthly',
        'plan_trial_days' => 14,
        'plan_type'    => 'month',
        'plan_length'  => 1,
        'main_currency_code' => 'USD',
        'recurring_price_usd' => '29.99',
    ])
    ->send();
```

### List Subscription Users

```php
$users = Paddle::subscription()
    ->listUsers()
    ->send();
```

### Update Subscription User

```php
$response = Paddle::subscription()
    ->updateUser([
        'subscription_id' => $subscriptionId,
        'plan_id'         => $newPlanId,
        'prorate'         => true,
    ])
    ->send();
```

### Preview Update

```php
$preview = Paddle::subscription()
    ->previewUpdate([
        'subscription_id' => $subscriptionId,
        'plan_id'         => $newPlanId,
    ])
    ->send();
```

### Cancel Subscription

```php
$response = Paddle::subscription()
    ->cancelUser([
        'subscription_id' => $subscriptionId,
    ])
    ->send();
```

### Create One-Off Charge

```php
$response = Paddle::subscription()
    ->createOneOffCharge($subscriptionId, [
        'amount' => 9.99,
        'charge_name' => 'Setup Fee',
    ])
    ->send();
```

### List Payments

```php
$payments = Paddle::subscription()
    ->listPayments([
        'subscription_id' => $subscriptionId,
    ])
    ->send();
```

### Reschedule Payment

```php
$response = Paddle::subscription()
    ->reschedulePayment([
        'payment_id' => $paymentId,
        'date'       => '2025-02-15',
    ])
    ->send();
```

### Subscription Modifiers

```php
// List modifiers
$modifiers = Paddle::subscription()
    ->listModifiers()
    ->send();

// Create modifier
$modifier = Paddle::subscription()
    ->createModifier([
        'subscription_id'   => $subscriptionId,
        'modifier_amount'   => '5.00',
        'modifier_recurring' => true,
    ])
    ->send();

// Delete modifier
Paddle::subscription()
    ->deleteModifier([
        'modifier_id' => $modifierId,
    ])
    ->send();
```

## Checkout API

### Get Order Details

```php
$order = Paddle::checkout()
    ->getOrderDetails([
        'checkout_id' => $checkoutId,
    ])
    ->send();
```

### Get User History

```php
$history = Paddle::checkout()
    ->getUserHistory([
        'email' => $userEmail,
    ])
    ->send();
```

### Get Prices

```php
$prices = Paddle::checkout()
    ->getPrices([
        'product_ids' => '123,456',
    ])
    ->send();
```

## Coupon API

```php
// List coupons
$coupons = Paddle::product()
    ->listCoupons([
        'product_id' => $productId,
    ])
    ->send();

// Create coupon
$coupon = Paddle::product()
    ->createCoupon([
        'coupon_type'     => 'product',
        'discount_type'   => 'percentage',
        'discount_amount' => 25,
        'coupon_code'     => 'SAVE25',
        'product_ids'     => '123,456',
    ])
    ->send();

// Update coupon
Paddle::product()
    ->updateCoupon([
        'coupon_code'     => 'SAVE25',
        'new_coupon_code' => 'SAVE30',
        'discount_amount' => 30,
    ])
    ->send();

// Delete coupon
Paddle::product()
    ->deleteCoupon([
        'coupon_code' => 'SAVE30',
        'product_id'  => $productId,
    ])
    ->send();
```

## Alert API

### Get Webhook History

```php
$webhooks = Paddle::alert()
    ->getWebhookHistory()
    ->send();
```

## Webhook Events

The package automatically registers a webhook route at `config('paddle.webhook_uri')` (default: `paddle/webhook`). Incoming webhooks are verified using your public key and dispatched as Laravel events.

### Available Events

All events extend `ProtoneMedia\LaravelPaddle\Events\Event` and live in the `ProtoneMedia\LaravelPaddle\Events` namespace:

| Event Class | Paddle Alert Name |
|---|---|
| `SubscriptionCreated` | `subscription_created` |
| `SubscriptionUpdated` | `subscription_updated` |
| `SubscriptionCancelled` | `subscription_cancelled` |
| `SubscriptionPaymentSucceeded` | `subscription_payment_succeeded` |
| `SubscriptionPaymentFailed` | `subscription_payment_failed` |
| `SubscriptionPaymentRefunded` | `subscription_payment_refunded` |
| `PaymentSucceeded` | `payment_succeeded` |
| `PaymentRefunded` | `payment_refunded` |
| `PaymentDisputeCreated` | `payment_dispute_created` |
| `PaymentDisputeClosed` | `payment_dispute_closed` |
| `HighRiskTransactionCreated` | `high_risk_transaction_created` |
| `HighRiskTransactionUpdated` | `high_risk_transaction_updated` |
| `TransferCreated` | `transfer_created` |
| `TransferPaid` | `transfer_paid` |
| `NewAudienceMember` | `new_audience_member` |
| `UpdateAudienceMember` | `update_audience_member` |
| `LockerProcessed` | `locker_processed` |
| `GenericWebhook` | *(fallback for unknown alert names)* |

### Listening to Events

Register listeners in your `EventServiceProvider`:

```php
protected $listen = [
    \ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated::class => [
        \App\Listeners\HandleSubscriptionCreated::class,
    ],
    \ProtoneMedia\LaravelPaddle\Events\PaymentSucceeded::class => [
        \App\Listeners\HandlePaymentSucceeded::class,
    ],
];
```

### Event Methods

All webhook events provide these methods:

```php
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;

class HandleSubscriptionCreated
{
    public function handle(SubscriptionCreated $event)
    {
        // Access webhook data as properties
        $status = $event->status;
        $email = $event->email;
        $subscriptionId = $event->subscription_id;
        $nextBillDate = $event->next_bill_date;

        // passthrough is auto-decoded from JSON
        $passthrough = $event->passthrough;
        $userId = $passthrough['user_id'];

        // Get all webhook data as array
        $allData = $event->all();

        // Get the underlying HTTP request
        $request = $event->getRequest();
    }
}
```

## Blade Directive

Include Paddle.js and configure it with your vendor ID:

```blade
<body>
    {{-- your app --}}

    @paddle
</body>
```

This renders:
- The Paddle.js script tag
- `Paddle.Setup({ vendor: YOUR_VENDOR_ID })` call
- Sandbox configuration if `PADDLE_SANDBOX=true`

### Opening Checkout with Paddle.js

After including `@paddle`, use Paddle.js in your frontend:

```html
<a href="#!" data-product="{{ $productId }}" class="paddle_button">Buy Now</a>
```

Or via JavaScript:

```javascript
Paddle.Checkout.open({
    product: productId,
    email: userEmail,
    passthrough: JSON.stringify({ user_id: userId }),
    successCallback: function(data) { /* handle success */ },
    closeCallback: function() { /* handle close */ }
});
```

## Request Building

All API methods support both fluent and array styles:

### Fluent Style

Method names are camelCase versions of Paddle API parameters:

```php
Paddle::product()
    ->generatePayLink()
    ->productId(123)
    ->title('My Product')
    ->webhookUrl('https://example.com/webhook')
    ->prices(['USD:19.99', 'EUR:17.99'])
    ->customerEmail('user@example.com')
    ->customerCountry('US')
    ->passthrough(['user_id' => 1])
    ->returnUrl('https://example.com/thank-you')
    ->send();
```

### Array Style

Keys match the Paddle API parameter names (snake_case):

```php
Paddle::product()
    ->generatePayLink([
        'product_id'       => 123,
        'title'            => 'My Product',
        'webhook_url'      => 'https://example.com/webhook',
        'prices'           => ['USD:19.99', 'EUR:17.99'],
        'customer_email'   => 'user@example.com',
        'customer_country' => 'US',
        'passthrough'      => ['user_id' => 1],
        'return_url'       => 'https://example.com/thank-you',
    ])
    ->send();
```

## Error Handling

```php
use ProtoneMedia\LaravelPaddle\Api\PaddleApiException;
use ProtoneMedia\LaravelPaddle\Api\InvalidDataException;

try {
    $response = Paddle::product()
        ->generatePayLink()
        ->productId($planId)
        ->send();
} catch (PaddleApiException $e) {
    // API returned an error (e.g., invalid credentials, bad request)
    $message = $e->getMessage();
} catch (InvalidDataException $e) {
    // Request validation failed before sending
    $errors = $e->getMessages();
}
```

## Sandbox Environment

Set `PADDLE_SANDBOX=true` in your `.env` to use the Paddle Sandbox for development and testing. The package automatically routes API calls to sandbox endpoints and configures `@paddle` Blade directive for sandbox mode.
