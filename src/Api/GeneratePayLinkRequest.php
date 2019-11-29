<?php

namespace ProtoneMedia\LaravelPaddle\Api;

class GeneratePayLinkRequest extends Request
{
    /**
     * The 'passthrough' attribute must be a string if the given $data is
     * not a string, we will encode it in JSON.
     *
     * @param  mixed $data
     * @return $this
     */
    public function passthrough($data)
    {
        return $this->setAttribute('passthrough', is_string($data) ? $data : json_encode($data));
    }
}
