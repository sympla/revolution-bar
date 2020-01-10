<?php
namespace RDStation\Services;

use RDStation\Configuration\Routes;
use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\RequestFailed;
use RDStation\Response\AuthorizationResponse;
use RDStation\Helpers\Request;
use RDStation\Helpers\BuildUrl;
use GuzzleHttp\Client;

class Authorization
{

    /** @var string $clientId */
    protected $clientId;

    /** @var string */
    private $code;

    /** @var string $clientSecret */
    private $clientSecret;

    /**
     * Authentication constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $code
     */
    public function __construct(string $clientId, string $clientSecret, string $code)
    {
        $this->code    = $code;
        $this->clientSecret = $clientSecret;
        $this->clientId = $clientId;
    }

    /**
     * @return AuthorizationResponse
     * @throws \JsonException
     * @throws ContentTypeInvalid
     * @throws RequestFailed
     * @throws ContentTypeInvalid
     * @throws \RDStation\Exception\InvalidRouteException
     */
    public function getAccessToken() : AuthorizationResponse
    {
        $url = BuildUrl::getUrlByRoute(Routes::AUTHORIZATION);
        $parameters = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $this->code
        ];


        return $this->generateAuthorizationResponse(
            $this->getInstanceRequest()->post(sprintf('%s', $url), $parameters)
        );
    }

    /**
     * @param array $response
     * @return AuthorizationResponse
     */
    protected function generateAuthorizationResponse(array $response) : AuthorizationResponse
    {
        return new AuthorizationResponse(
            $response['access_token'],
            $response['refresh_token'],
            $response['expires_in']
        );
    }

    /**
     * 
     * @return Request;
     */
    public function getInstanceRequest() : Request
    {
        return new Request(
            new Client([
                'verify' => false,
                'connect_timeout' => 6,
                'timeout' => 0,
                'headers' => []
            ])
        );
    }
}
