<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Alert
{
    /**
     * https://developer.paddle.com/api-reference/alert-api/webhooks/webhooks
     */
    public function getWebhookHistory(array $data = [])
    {
        return new Request('/2.0/alert/webhooks', $data);
    }
}
