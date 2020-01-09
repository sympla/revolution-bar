<?php


namespace RDStation\Services;

use RDStation\Configuration\Routes;

class Authentication
{
    /** @var string $clientId */
    private $clientId;

    /** @var string $redirectUrl */
    private $redirectUrl;

    /**
     * Authentication constructor.
     * @param string $clientId
     * @param string $redirectUrl
     */
    public function __construct(string $clientId, string $redirectUrl)
    {
        $this->clientId = $clientId;
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return string
     * @throws \RDStation\Exception\InvalidRouteException
     */
    public function getUrlAuthentication()
    {
        $url = \BuidUrl::getUrlByRoute(Routes::AUTHENTICATION);
        $parameters = [
            'client_id' => $this->clientId,
            'redirect_url' => $this->redirectUrl
        ];

        return sprintf("%s?%s", $url, http_build_query($parameters));
    }
}