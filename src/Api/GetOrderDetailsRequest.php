<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class GetOrderDetailsRequest extends Request
{
    public function url(): string
    {
        return "https://checkout.paddle.com/api{$this->uri}";
    }
}
