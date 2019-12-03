<?php

namespace ProtoneMedia\LaravelPaddle;

use Illuminate\Support\Facades\Facade;

/**
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
