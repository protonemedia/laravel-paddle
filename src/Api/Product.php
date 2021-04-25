<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class Product
{
    use HandlesCoupons;

    /**
     * https://developer.paddle.com/api-reference/product-api/products/getproducts
     */
    public function listProducts(array $data = [])
    {
        return new Request('/2.0/product/get_products', $data);
    }

    /**
     * https://developer.paddle.com/api-reference/product-api/licenses/createlicense
     */
    public function generateLicense(array $data = [])
    {
        return new Request('/2.0/product/generate_license', $data, [
            'product_id'   => 'required|numeric',
            'allowed_uses' => 'required|integer',
        ]);
    }

    /**
     * https://developer.paddle.com/api-reference/product-api/pay-links/createpaylink
     */
    public function generatePayLink(array $data = [])
    {
        return new GeneratePayLinkRequest('/2.0/product/generate_pay_link', $data, [
            'product_id'                => 'numeric',
            'title'                     => 'required_without:product_id',
            'webhook_url'               => 'required_without:product_id',
            'prices'                    => 'required_without:product_id',
            'custom_message'            => 'max:255',
            'quantity'                  => 'min:1|max:100',
            'recurring_affiliate_limit' => 'min:1',
            'customer_email'            => 'email',
            'return_url'                => 'url',
        ]);
    }

    /**
     * https://developer.paddle.com/api-reference/product-api/transactions/listtransactions
     */
    public function listTransactions($entity, $id, array $data = [])
    {
        return new Request(sprintf('/2.0/%s/%s/transactions', $entity, $id), $data);
    }
}
