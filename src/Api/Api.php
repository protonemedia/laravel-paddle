<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Api
{
    /**
     * @return \ProtoneMedia\LaravelPaddle\Api\Product
     */
    public function product()
    {
        return new Product;
    }

    /**
     * @return \ProtoneMedia\LaravelPaddle\Api\Subscription
     */
    public function subscription()
    {
        return new Subscription;
    }
}
