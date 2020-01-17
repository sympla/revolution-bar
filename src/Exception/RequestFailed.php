<?php

namespace RDStation\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;

class RequestFailed extends Exception
{
    /** @var int $statusCode */
    protected $statusCode;

    /** @var ResponseInterface $response */
    protected $response;

    public function __construct($message, $statusCode, ResponseInterface $response)
    {
        $this->statusCode = $statusCode;
        $this->response = $response;

        parent::__construct($message, $statusCode);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
