<?php

namespace ProtoneMedia\LaravelPaddle\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Request
{
    /**
     * Paddle API Endpoint.
     */
    const API_ENDPOINT = "https://vendors.paddle.com/api";

    /**
     * Method options.
     */
    const METHOD_GET  = 'get';
    const METHOD_POST = 'post';

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @var string
     */
    private $method;

    /**
     * Creates an instance with the URI and data.
     *
     * @param string $uri
     * @param array  $data
     * @param array  $rules
     * @param string $method
     */
    public function __construct(string $uri, array $data = [], array $rules = [], string $method = self::METHOD_POST)
    {
        $this->uri    = $uri;
        $this->rules  = $rules;
        $this->method = $method;

        $this->fill($data);
    }

    /**
     * Formats the URL to send the request to.
     *
     * @return string
     */
    public function url(): string
    {
        return static::API_ENDPOINT . $this->uri;
    }

    /**
     * Validates the data with the rules.
     *
     * @return $this
     * @throws \ProtoneMedia\LaravelPaddle\Api\InvalidDataException
     */
    protected function validateData()
    {
        tap(Validator::make($this->data, $this->rules), function ($validator) {
            if ($validator->passes()) {
                return;
            }

            throw InvalidDataException::fromValidator($validator);
        });

        return $this;
    }

    /**
     * Sends the data payload to the uri and returns to decoded response.
     *
     * @return mixed
     *
     * @throws \ProtoneMedia\LaravelPaddle\Api\PaddleApiException
     */
    public function send()
    {
        $this->validateData();

        $method = $this->method;

        $data = $this->getData() + ['vendor_id' => config('paddle.vendor_id')];

        if ($method === static::METHOD_POST) {
            $data['vendor_auth_code'] = config('paddle.vendor_auth_code');
        }

        $response = app('laravel-paddle.http')
            ->asFormParams()
            ->$method($this->url(), $data);

        if (!$response->isSuccess()) {
            throw PaddleApiException::unsuccessfulStatus($response->status());
        }

        $json = $response->json();

        if ($json['success'] ?? null) {
            return $json['response'];
        }

        throw PaddleApiException::fromResponse($json);
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
