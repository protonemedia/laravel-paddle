<?php

namespace ProtoneMedia\LaravelPaddle\Events;

use Illuminate\Support\Str;

abstract class Event
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fire(array $data)
    {
        $event = Str::studly($data['alert_name']);

        $eventClass = __NAMESPACE__ . '\\' . $event;

        event(new $eventClass($data));
    }
}
