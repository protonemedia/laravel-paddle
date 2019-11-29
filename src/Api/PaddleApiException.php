<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class PaddleApiException extends \Exception
{
    public static function fromResponse(array $response)
    {
        return new static("[{$response['error']['code']}] {$response['error']['message']}");
    }
}
