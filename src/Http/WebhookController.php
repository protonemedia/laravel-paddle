<?php

namespace ProtoneMedia\LaravelPaddle\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelPaddle\Events\Event;

class WebhookController
{
    /**
     * https://developer.paddle.com/webhook-reference/verifying-webhooks
     *
     * @param  string $encodedSignature
     * @param  array  $data
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function verifySignature(string $encodedSignature, array $data)
    {
        ksort($data);

        foreach ($data as $key => $value) {
            if (in_array(gettype($value), ['object', 'array'])) {
                continue;
            }

            $data[$key] = "$value";
        }

        $verified = openssl_verify(
            serialize($data),
            base64_decode($encodedSignature),
            openssl_get_publickey(config('laravel-paddle.public_key')),
            OPENSSL_ALGO_SHA1
        );

        if ($verified !== 1) {
            abort(403);
        }
    }

    /**
     * Verify the request signature and fire the event.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $data = Arr::except($request->all(), $signatureKey = 'p_signature');

        $this->verifySignature($request->input($signatureKey), $data);

        Event::fire($data);
    }
}
