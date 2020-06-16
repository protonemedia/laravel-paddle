<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class CheckoutRequest extends Request
{
    public function url(): string
    {
        return "https://checkout.paddle.com/api{$this->uri}";
    }
}
