<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class GeneratePayLinkRequest extends Request
{
    public function passthrough($data)
    {
        return $this->setAttribute('passthrough', is_string($data) ? $data : json_encode($data));
    }
}
