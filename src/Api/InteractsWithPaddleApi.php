<?php

namespace ProtoneMedia\LaravelPaddle\Api;

trait InteractsWithPaddleApi
{
    private function uri(string $uri): string
    {
        return "https://vendors.paddle.com/api/2.0/{$uri}";
    }

    private function post(string $uri, array $data = [])
    {
        $response = app('laravel-paddle.http')
            ->asFormParams()
            ->post($this->uri($uri), [
                'vendor_id'        => config('paddle.vendor_id'),
                'vendor_auth_code' => config('paddle.vendor_auth_code'),
            ] + $data);

        if (!$response->isSuccess()) {
            throw PaddleApiException::unsuccessfulStatus($response->status());
        }

        $json = $response->json();

        if ($json['success'] ?? null) {
            return $json['response'];
        }

        throw PaddleApiException::fromResponse($json);
    }
}
