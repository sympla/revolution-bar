<?php

namespace RDStation\Services;

use RDStation\Configuration\Routes;
use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\RequestFailed;
use RDStation\Response\AuthorizationResponse;
use RDStation\Helpers\Request;

require_once __DIR__ . "/../vendor/autoload.php";

class Authorization
{
    /** @var Request $request */
    protected $request;

    /** @var string $clientId */
    protected $clientId;

    /** @var string $callbackUrl */
    protected $callbackUrl;

    /** @var RDStationConfiguration $configuration */
    protected $configuration;

    /** @var string */
    private $code;

    /** @var string $clientSecret */
    private $clientSecret;

    /**
     * Authentication constructor.
     * @param string $clientId
     * @param string $code
     * @param string $clientSecret
     */
    public function __construct(string $clientId, string $code, string $clientSecret)
    {
        $this->request     = new Request([]);
        $this->code    = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return AuthorizationResponse
     * @throws \JsonException
     * @throws ContentTypeInvalid
     * @throws RequestFailed
     * @throws ContentTypeInvalid
     * @throws \RDStation\Exception\InvalidRouteException
     */
    public function getAccessToken()
    {
        $url = \BuidUrl::getUrlByRoute(Routes::AUTHORIZATION);
        $parameters = [
            'client_id' => $this->clientId,
            'redirect_url' => $this->callbackUrl
        ];

        return $this->generateAuthenticateResponse(
            $this->request->post(sprintf('%s', $url), $parameters)
        );
    }

    /**
     * @param array $response
     * @return AuthorizationResponse
     */
    private function generateAuthenticateResponse(array $response)
    {
        return new AuthorizationResponse(
            $response['access_token'],
            $response['refresh_token'],
            $response['expire_in']
        );
    }
}