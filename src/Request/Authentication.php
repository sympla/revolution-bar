<?php

namespace RDStation\Request;

use RDStation\Configuration\Http;
use RDStation\Configuration\Routes;
use RDStation\Services\Request;

require_once __DIR__ . "/../vendor/autoload.php";

class Authentication
{
    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @var string $clientId
     */
    protected $clientId;

    /**
     * @var string $callbackUrl
     */
    protected $callbackUrl;

    /**
     * @var RDStationConfiguration $configuration
     */
    protected $configuration;

    /**
     * Authentication constructor.
     * @param string $clientId
     * @param string $callbackUrl
     */
    public function __construct(string $clientId, string $callbackUrl)
    {
        $this->request     = new Request([]);
        $this->clientId    = $clientId;
        $this->callbackUrl = $callbackUrl;
    }

    public function authenticate()
    {
        $url = sprintf('%s/%s', Http::BASE_URL, Routes::AUTH);
        $parameters = [
            'client_id' => $this->clientId,
            'redirect_url' => $this->callbackUrl
        ];

        $response = $this->request->get(sprintf('%s?%s', $url, http_build_query($parameters)));

        if ($response->)
    }
}