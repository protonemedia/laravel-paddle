<?php

namespace ProtoneMedia\LaravelPaddle\Events;

/**
 * Fired when a payment for a subscription is received successfully.
 */
class SubscriptionPaymentSucceeded extends Event
{
    // {
    //   "alert_id": "20068522",
    //   "alert_name": "subscription_payment_succeeded",
    //   "balance_currency": "USD",
    //   "balance_earnings": "0",
    //   "balance_fee": "0",
    //   "balance_gross": "0",
    //   "balance_tax": "0",
    //   "checkout_id": "43254208-chre074552c379d-4c8e917ba9",
    //   "country": "NL",
    //   "coupon": "696BDAC4",
    //   "currency": "EUR",
    //   "customer_name": "Pascal Baljet",
    //   "earnings": "0",
    //   "email": "pascal@protone.media",
    //   "event_time": "2019-11-29 12:04:55",
    //   "fee": "0",
    //   "initial_payment": "1",
    //   "instalments": "1",
    //   "marketing_consent": "0",
    //   "next_bill_date": "2019-12-29",
    //   "next_payment_amount": "0",
    //   "order_id": "10492877-7253841",
    //   "passthrough": "{\"team_id\":\"7b27ffb2-57ef-40d1-a2d3-3f38a0af2231\"}",
    //   "payment_method": "paypal",
    //   "payment_tax": "0",
    //   "plan_name": "Startup plan",
    //   "quantity": "1",
    //   "receipt_url": "http://my.paddle.com/receipt/10492877-7253841/43254208-chre074552c379d-4c8e917ba9",
    //   "sale_gross": "0",
    //   "status": "active",
    //   "subscription_id": "2341404",
    //   "subscription_payment_id": "7253841",
    //   "subscription_plan_id": "1",
    //   "unit_price": "5.00",
    //   "user_id": "12935642"
    // }
}
