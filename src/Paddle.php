<?php

namespace ProtoneMedia\LaravelPaddle;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \ProtoneMedia\LaravelPaddle\Api\Alert alert()
 * @method static \ProtoneMedia\LaravelPaddle\Api\Checkout checkout()
 * @method static \ProtoneMedia\LaravelPaddle\Api\Product product()
 * @method static \ProtoneMedia\LaravelPaddle\Api\Subscription subscription()
 *
 * @see \ProtoneMedia\LaravelPaddle\Api\Api
 */
class Paddle extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-paddle';
    }
}
