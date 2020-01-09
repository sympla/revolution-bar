<?php
namespace RDStation\Services;
use RDStation\Configuration\Routes;
use RDStation\Helpers\BuildUrl;
class Authentication
{
    /** @var string $clientId */
    private $clientId;
    /** @var string $redirectUrl */
    private $redirectUrl;
    /** @var array $parameters */
    private $params;
    /**
     * Authentication constructor.
     * @param string $clientId
     * @param string $redirectUrl
     */
    public function __construct(string $clientId, string $redirectUrl, array $params = [])
    {
        $this->clientId = $clientId;
        $this->redirectUrl = $redirectUrl;
        $this->params = $params;
    }
    /**
     * @return string
     * @throws \RDStation\Exception\InvalidRouteException
     */
    public function getUrlAuthentication()
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