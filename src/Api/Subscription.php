<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Subscription
{
    public function plans()
    {
        return new Request('subscription/plans');
    }

    public function users()
    {
        return new Request('subscription/users');
    }

    public function payments()
    {
        return new Request('subscription/payments');
    }
}
