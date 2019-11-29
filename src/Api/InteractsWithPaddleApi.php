<?php

namespace ProtoneMedia\LaravelPaddle\Api;

use Zttp\PendingZttpRequest;

trait InteractsWithPaddleApi
{
    private function uri(string $uri): string
    {
        return "https://vendors.paddle.com/api/2.0/{$uri}";
    }

    private function post(string $uri, array $data = [])
    {
        $response = app(PendingZttpRequest::class)
            ->asFormParams()
            ->post($this->uri($uri), [
                'vendor_id'        => config('laravel-paddle.vendor_id'),
                'vendor_auth_code' => config('laravel-paddle.vendor_auth_code'),
            ] + $data);

        if (!$response->isSuccess()) {
            throw new PaddleApiException;
        }

        $json = $response->json();

        if ($json['success'] ?? null) {
            return $json['response'];
        }

        throw PaddleApiException::fromResponse($json);
    }
}
