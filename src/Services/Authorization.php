<?php

namespace RDStation\Services;

use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\InvalidRouteException;
use RDStation\Exception\JsonException;
use RDStation\Exception\RequestFailed;
use RDStation\Response\AuthorizationResponse;
use RDStation\Services\Contract\AuthorizationAbstract;
use ReflectionException;

class Authorization extends AuthorizationAbstract
{
    /**
     * @var string
     */
    private $code;

    /**
     * Authentication constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $code
     */
    public function __construct(string $clientId, string $clientSecret, string $code)
    {
        $this->code    = $code;
        parent::__construct($clientId, $clientSecret);
    }

    /**
     * @return array
     */
    protected function getParameters(): array
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $this->code
        ];
    }
}
