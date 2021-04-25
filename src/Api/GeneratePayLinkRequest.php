<?php

namespace ProtoneMedia\LaravelPaddle\Api;

/**
  * Class GeneratePayLinkRequest
  * @package ProtoneMedia\LaravelPaddle\Api
  * @method productId()
  * @method title()
  * @method webhookUrl()
  * @method prices()
  * @method customMessage()
  * @method quantity()
  * @method recurringAffiliateLimit()
  * @method customerEmail()
  * @method returnUrl()
  */
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
