<?php

namespace ProtoneMedia\LaravelPaddle\Api;

use Illuminate\Support\Str;

class Request
{
    use InteractsWithPaddleApi;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Creates an instance with the URI and data.
     *
     * @param string $uri
     * @param array  $data
     */
    public function __construct(string $uri, array $data = [])
    {
        $this->uri = $uri;

        $this->fill($data);
    }

    /**
     * Posts the data payload to the uri and returns to decoded response.
     *
     * @return mixed
     *
     * @throws \ProtoneMedia\LaravelPaddle\Api\PaddleApiException
     */
    public function send()
    {
        return $this->post($this->uri, $this->getData());
    }

    /**
     * Getter for the set data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Loops through the array to set all data attributes.
     *
     * @param  array  $data
     * @return $this
     */
    private function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $key = lcfirst(Str::studly($key));

            $this->$key($value);
        }
    }

    /**
     * Setter for the data.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setAttribute(string $key, $value = null)
    {
        $this->data[Str::snake($key)] = $value;

        return $this;
    }

    /**
     * To make the request fluent.
     *
     * @param  string $method
     * @param  array $parameters
     * @return $this
     */
    public function __call($method, $parameters)
    {
        return $this->setAttribute($method, $parameters[0] ?? null);
    }
}
