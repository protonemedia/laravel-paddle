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
     * Getter for all data.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->webhookData;
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
     * @return array|null
     */
    public static function fire(array $data)
    {
        if (!array_key_exists('alert_name', $data)) {
            return event(new GenericWebhook($data));
        }

        $event = Str::studly($data['alert_name']);

        $eventClass = __NAMESPACE__ . '\\' . $event;

        return event(new $eventClass($data));
    }
}
