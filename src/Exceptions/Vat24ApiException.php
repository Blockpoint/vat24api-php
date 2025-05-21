<?php

namespace Blockpoint\Vat24Api\Exceptions;

use Exception;

class Vat24ApiException extends Exception
{
    /**
     * Create a new Vat24ApiException instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
