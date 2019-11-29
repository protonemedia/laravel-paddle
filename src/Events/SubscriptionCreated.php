<?php

namespace ProtoneMedia\LaravelPaddle\Events;

/**
 * Fired when a new subscription is created, and a customer has successfully subscribed.
 */
class SubscriptionCreated extends Event
{
    // {
    //   "alert_id": "20068521",
    //   "alert_name": "subscription_created",
    //   "cancel_url": "https://checkout.paddle.com/subscription/cancel?user=12935642&subscription=2341404&hash=1fd0b0e2832ecb9502338eb342fbe5844950dce8df16c1c392a56b3f8ec61349",
    //   "checkout_id": "43254208-chre074552c379d-4c8e917ba9",
    //   "currency": "EUR",
    //   "email": "pascal@protone.media",
    //   "event_time": "2019-11-29 12:04:55",
    //   "marketing_consent": "0",
    //   "next_bill_date": "2019-12-29",
    //   "passthrough": "{\"team_id\":\"7b27ffb2-57ef-40d1-a2d3-3f38a0af2231\"}",
    //   "quantity": "1",
    //   "source": "laravel.test / laravel.test",
    //   "status": "active",
    //   "subscription_id": "2341404",
    //   "subscription_plan_id": "577044",
    //   "unit_price": "5.00",
    //   "update_url": "https://checkout.paddle.com/subscription/update?user=12935642&subscription=2341404&hash=b07c9093acb44648a17ad7401b1455fcc80f4a8dcab275ce4de35fa86434a284",
    //   "user_id": "12935642"
    // }
}
