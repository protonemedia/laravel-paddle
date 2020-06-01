<?php

namespace ProtoneMedia\LaravelPaddle\Events;

/**
 * Fired when the data has no 'alert_name' key.
 * For example: Fulfillment Webhook
 * https://developer.paddle.com/webhook-reference/product-fulfillment/fulfillment-webhook
 */
class GenericWebhook extends Event
{
}
