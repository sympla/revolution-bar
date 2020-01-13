<?php

namespace RDStation\Exception;


class JsonException extends \Exception {

    public function __construct(string $message = null, int $code = 0, \Throwable $throwable = null)
    {
        $message = $message ?? json_last_error_msg();
        $code = $code ?? json_last_error();
        parent::__construct($message, $code, $throwable);
    }
}