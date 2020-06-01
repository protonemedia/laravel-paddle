<?php

namespace ProtoneMedia\LaravelPaddle\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Event
{
    /**
     * @var array
     */
    protected $webhookData;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(array $webhookData, Request $request)
    {
        $this->webhookData = $webhookData;
        $this->request     = $request;
    }

    /**
     * Getter for all data.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->webhookData;
    }

    /**
     * Getter for the request.
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Getter for the webhook data.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        $value = $this->webhookData[$key];

        if ($key === 'passthrough') {
            $result = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $result;
            }
        }

        return $value;
    }

    /**
     * Determine if an attribute exists on the webhook data.
     *
     * @param  string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->webhookData[$key]);
    }

    /**
     * Generates the event class name with the 'alert_name' attribute from
     * the data and fires the event with the data.
     *
     * @param  array  $data
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public static function fire(array $data, Request $request)
    {
        $event = Str::studly($data['alert_name'] ?? 'generic_webhook');

        $eventClass = __NAMESPACE__ . '\\' . $event;

        return event(new $eventClass($data, $request));
    }
}
