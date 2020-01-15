<?php

namespace RDStation\Services\Contract;

use RDStation\Configuration\Routes;
use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\InvalidRouteException;
use RDStation\Exception\JsonException;
use RDStation\Exception\RequestFailed;
use RDStation\Helpers\BuildUrl;
use RDStation\Response\AuthorizationResponse;
use RDStation\Services\Traits\InstanceRequest;
use ReflectionException;

abstract class AuthorizationAbstract
{
    use InstanceRequest;

    /** @var string $clientId */
    protected $clientId;

    /** @var string $clientSecret */
    protected $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
        $this->clientId = $clientId;
    }

    /**
     * @return string
     * @throws InvalidRouteException
     * @throws ReflectionException
     */
    protected function getUrlAuthorization()
    {
        return BuildUrl::getUrlByRoute(Routes::AUTHORIZATION);
    }

    /**
     * @param  array $response
     * @return AuthorizationResponse
     */
    protected function generateAuthorizationResponse(array $response): AuthorizationResponse
    {
        return new AuthorizationResponse(
            $response['access_token'],
            $response['refresh_token'],
            $response['expires_in']
        );
    }

    /**
     * Return parameter's request
     * @return array
     */
    abstract protected function getParameters(): array;

    /**
     * @return AuthorizationResponse
     * @throws InvalidRouteException
     * @throws ContentTypeInvalid
     * @throws JsonException
     * @throws RequestFailed
     * @throws ReflectionException
     */
    public function execute(): AuthorizationResponse
    {
        $url = $this->getUrlAuthorization();
        $parameters = $this->getParameters();

        return $this->generateAuthorizationResponse(
            $this->getInstanceRequest()->post(sprintf('%s', $url), $parameters)
        );
    }
}
