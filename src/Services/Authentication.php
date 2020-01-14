<?php

namespace RDStation\Services;

use RDStation\Configuration\Routes;
use RDStation\Helpers\BuildUrl;
use RDStation\Exception\InvalidRouteException;
use ReflectionException;

class Authentication
{
    /**
     * @var string $clientId
     */
    private $clientId;

    /**
     * @var string $redirectUrl
     */
    private $redirectUrl;

    /**
     * @var array $parameters
     */
    private $params;

    /**
     * Authentication constructor.
     * @param string $clientId
     * @param string $redirectUrl
     * @param array $params
     */
    public function __construct(string $clientId, string $redirectUrl, array $params = [])
    {
        $this->clientId = $clientId;
        $this->redirectUrl = $redirectUrl;
        $this->params = $params;
    }

    /**
     * @return string
     * @throws InvalidRouteException
     * @throws ReflectionException
     */
    public function getUrlAuthentication() : string
    {
        $url = BuildUrl::getUrlByRoute(Routes::AUTHENTICATION);
        $parameters = [
            'client_id' => $this->clientId,
            'redirect_url' => $this->redirectUrl,
            'params' => $this->params,
        ];
        return sprintf("%s?%s", $url, http_build_query($parameters));
    }
}