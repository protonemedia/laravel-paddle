<?php

namespace Protonemedia\LaravelPaddle;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Protonemedia\LaravelPaddle\Skeleton\SkeletonClass
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
