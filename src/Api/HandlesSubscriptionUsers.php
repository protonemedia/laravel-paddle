<?php

namespace ProtoneMedia\LaravelPaddle\Api;

trait HandlesSubscriptionUsers
{
    /**
     * https://developer.paddle.com/api-reference/subscription-api/subscription-users/listusers
     */
    public function listUsers(array $data = [])
    {
        return new Request('/2.0/subscription/users', $data, [
            'state' => 'required|in:active,past_due,trialling,deleted,paused',
        ]);
    }

    /**
     * https://developer.paddle.com/api-reference/subscription-api/subscription-users/canceluser
     */
    public function cancelUser(array $data = [])
    {
        return new Request('/2.0/subscription/users_cancel', $data, [
            'subscription_id' => 'required',
        ]);
    }

    /**
     * https://developer.paddle.com/api-reference/subscription-api/subscription-users/updateuser
     */
    public function updateUser(array $data = [])
    {
        return new Request('/2.0/subscription/users/update', $data, [
            'subscription_id' => 'required',
        ]);
    }

    /**
     * https://developer.paddle.com/api-reference/subscription-api/subscription-users/previewupdate
     */
    public function previewUpdate(array $data = [])
    {
        return new Request('/2.0/subscription/preview_update', $data, [
            'subscription_id' => 'required',
        ]);
    }
}
