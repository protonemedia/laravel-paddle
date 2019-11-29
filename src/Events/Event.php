<?php

namespace ProtoneMedia\LaravelPaddle\Events;

use Illuminate\Support\Str;

abstract class Event
{
    /**
     * @var array
     */
    protected $webhookData;

    public function __construct(array $webhookData)
    {
        $this->webhookData = $webhookData;
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
     * Generates the event class name with the 'alert_name' attribute from
     * the data and fires the event with the data.
     *
     * @param  array  $data
     * @return void
     */
    public static function fire(array $data)
    {
        $event = Str::studly($data['alert_name']);

        $eventClass = __NAMESPACE__ . '\\' . $event;

        event(new $eventClass($data));
    }
}
