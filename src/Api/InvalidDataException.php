<?php

namespace ProtoneMedia\LaravelPaddle\Api;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\Validator;

class InvalidDataException extends \Exception
{
    /**
     * @var \Illuminate\Contracts\Support\MessageBag
     */
    private $messages;

    /**
     * Getter for the messages
     *
     * @return \Illuminate\Contracts\Support\MessageBag $messages
     */
    public function getMessages(): MessageBag
    {
        return $this->messages;
    }

    /**
     * Setter for the messages
     *
     * @param \Illuminate\Contracts\Support\MessageBag $messages
     */
    public function setMessages(MessageBag $messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Creates an instance for invalid data
     *
     * @param  \Illuminate\Contracts\Validation\Validator    $validator
     * @return $this
     */
    public static function fromValidator(Validator $validator)
    {
        return (new static("The given data is invalid"))->setMessages($validator->messages());
    }
}
