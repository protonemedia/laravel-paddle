<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Subscription
{
    public function plans(array $data = [])
    {
        return new Request('subscription/plans', $data);
    }

    public function users(array $data = [])
    {
        return new Request('subscription/users', $data);
    }

    public function payments(array $data = [])
    {
        return new Request('subscription/payments', $data);
    }
}
