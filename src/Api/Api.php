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
}
