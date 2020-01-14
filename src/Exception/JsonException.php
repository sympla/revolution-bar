<?php

namespace RDStation\Exception;

use Exception;
use Throwable;

class JsonException extends Exception
{

    /**
     * JsonException constructor.
     *
     * @param string|null    $message
     * @param int            $code
     * @param Throwable|null $throwable
     */
    public function __construct(string $message = null, int $code = 0, Throwable $throwable = null)
    {
        $message = $message ?? json_last_error_msg();
        $code = $code ?? json_last_error();
        parent::__construct($message, $code, $throwable);
    }
}
