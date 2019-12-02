<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Subscription
{
    use HandlesPlans;
    use HandlesSubscriptionUsers;
    use HandlesModifiers;
    use HandlesPayments;

    /**
     * https://developer.paddle.com/api-reference/subscription-api/one-off-charges/createcharge
     */
    public function createOneOffCharge($id, array $data = [])
    {
        return new Request(sprintf('/2.0/subscription/%s/charge', $id), $data);
    }
}
