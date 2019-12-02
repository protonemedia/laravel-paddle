<?php

namespace ProtoneMedia\LaravelPaddle\Api;

trait HandlesModifiers
{
    /**
     * https://developer.paddle.com/api-reference/subscription-api/modifiers/listmodifiers
     */
    public function listModifiers(array $data = [])
    {
        return new Request('/2.0/subscription/modifiers', $data);
    }

    /**
     * https://developer.paddle.com/api-reference/subscription-api/modifiers/createmodifier
     */
    public function createModifier(array $data = [])
    {
        return new Request('/2.0/subscription/modifiers/create', $data, [
            'subscription_id' => 'required',
            'modifier_amount' => 'required|numeric',
        ]);
    }

    /**
     * https://developer.paddle.com/api-reference/subscription-api/modifiers/deletemodifier
     */
    public function deleteModifier(array $data = [])
    {
        return new Request('/2.0/subscription/modifiers/delete', $data, [
            'modifier_id' => 'required',
        ]);
    }
}
