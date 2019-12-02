<?php

namespace ProtoneMedia\LaravelPaddle\Api;

trait HandlesCoupons
{
    /**
     * https://developer.paddle.com/api-reference/product-api/coupons/listcoupons
     */
    public function listCoupons(array $data = [])
    {
        return new Request('/2.0/product/list_coupons', $data, [
            'product_id' => 'numeric',
        ]);
    }

    /**
     * https://developer.paddle.com/api-reference/product-api/coupons/createcoupon
     */
    public function createCoupon(array $data = [])
    {
        return new Request('/2.1/product/create_coupon', $data, [
            'coupon_type'     => 'required|in:product,checkout',
            'discount_type'   => 'required|in:flat,percentage',
            'discount_amount' => 'required',
            'allowed_uses'    => 'required|integer',
        ]);
    }

    /**
     * https://developer.paddle.com/api-reference/product-api/coupons/deletecoupon
     */
    public function updateCoupon(array $data = [])
    {
        return new Request('/2.1/product/update_coupon', $data);
    }

    /**
     * https://developer.paddle.com/api-reference/product-api/coupons/deletecoupon
     */
    public function deleteCoupon(array $data = [])
    {
        return new Request('/2.0/product/delete_coupon', $data, [
            'coupon_code' => 'required',
        ]);
    }
}
