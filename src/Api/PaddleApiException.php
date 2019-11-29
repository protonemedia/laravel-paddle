<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class PaddleApiException extends \Exception
{
    public static function unsuccessfulStatus(int $status)
    {
        return new static("Response with status code {$status}");
    }

    public static function fromResponse(array $response)
    {
        return new static("[{$response['error']['code']}] {$response['error']['message']}");
    }
}
