<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Checkout
{
    /**
     * https://developer.paddle.com/api-reference/checkout-api/order-information/getorder
     */
    public function getOrderDetails(array $data = [])
    {
        return new CheckoutRequest('/1.0/order', $data, [], Request::METHOD_GET);
    }

    /**
     * https://developer.paddle.com/api-reference/checkout-api/user-history/getuserhistory
     */
    public function getUserHistory(array $data = [])
    {
        return new CheckoutRequest('/2.0/user/history', $data, [
            'email'      => 'required|email',
            'product_id' => 'numeric',
        ], Request::METHOD_GET);
    }

    /**
     * https://developer.paddle.com/api-reference/checkout-api/prices/getprices
     */
    public function getPrices(array $data = [])
    {
        return new CheckoutRequest('/2.0/prices', $data, [
            'product_ids' => 'required',
        ], Request::METHOD_GET);
    }
}
