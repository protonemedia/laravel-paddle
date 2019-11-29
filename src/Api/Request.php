<?php

namespace ProtoneMedia\LaravelPaddle\Api;

use Illuminate\Support\Str;

class Request
{
    use InteractsWithPaddleApi;

    private $uri;
    private $data = [];

    public function __construct(string $uri, array $data = [])
    {
        $this->uri  = $uri;
        $this->data = $data;
    }

    public function send()
    {
        return $this->post($this->uri, $this->data);
    }

    protected function setAttribute($key, $value = null)
    {
        $this->data[Str::snake($key)] = $value;

        return $this;
    }

    public function __call($method, $parameters)
    {
        return $this->setAttribute($method, $parameters[0] ?? null);
    }
}
