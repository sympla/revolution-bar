<?php

namespace RDStation\Helpers;

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\RequestFailed;
use RDStation\Exception\JsonException;
use GuzzleHttp\Client;

class Request
{
    /**
     * @var Client
     */
    private $httpClient;
    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @param $endpoint
     * @return array|mixed
     * @throws RequestFailed
     * @throws JsonException
     */
    public function get($endpoint): array
    {
        return $this->call('GET', $endpoint);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return mixed
     * @throws RequestFailed
     * @throws JsonException
     */
    public function post($endpoint, array $data = []): array
    {
        return $this->call('POST', $endpoint, ['form_params' => $data]);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return array
     * @throws RequestFailed
     * @throws JsonException
     */
    public function put($endpoint, array $data = []): array
    {
        return $this->call('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return array
     * @throws JsonException
     * @throws RequestFailed
     */
    public function patch($endpoint, array $data = []): array
    {
        return $this->call('PATCH', $endpoint, ['json' => $data]);
    }

    /**
     * @param $method
     * @param $endpoint
     * @param array $options
     * @return mixed
     * @throws RequestFailed
     * @throws JsonException
     */
    protected function call($method, $endpoint, array $options = []): array
    {

        try {
            $response = $this->httpClient->request($method, $endpoint, $options);
            $this->validateResponse($response);
            $content = $response->getBody()->getContents();
            $result = json_decode($content, true);

            if (json_last_error() != JSON_ERROR_NONE) {
                throw new JsonException();
            }

            return $result;
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            throw new RequestFailed("Client Exception", $response->getStatusCode(), $response);
        }
    }

    /**
     * @param ResponseInterface $response
     * @throws RequestFailed
     */
    private function validateResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new RequestFailed("Request Failed", $response->getStatusCode(), $response);
        }
    }
}
