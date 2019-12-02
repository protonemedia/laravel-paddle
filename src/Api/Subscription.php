<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Subscription
{
    public function listPlans(array $data = [])
    {
        return new Request('subscription/plans', $data);
    }

    //

    public function listUsers(array $data = [])
    {
        return new Request('subscription/users', $data);
    }

    public function updateSubscription(array $data = [])
    {
        return new Request('subscription/users/update', $data);
    }

    //

    public function listPayments(array $data = [])
    {
        return new Request('subscription/payments', $data);
    }
}
