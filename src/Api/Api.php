<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Api
{
    /**
     * @return \ProtoneMedia\LaravelPaddle\Api\Alert
     */
    public function alert()
    {
        return new Alert;
    }

    /**
     * @return \ProtoneMedia\LaravelPaddle\Api\Checkout
     */
    public function checkout()
    {
        return new Checkout;
    }

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
