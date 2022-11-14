<?php

namespace Janhelke\Scryfall\Exception;

use Exception;

class ScryfallException extends Exception
{
    /**
     * ScryfallException constructor.
     * @param null $message
     */
    public function __construct($message = null, int $code = 400)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
