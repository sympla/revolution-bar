<?php

namespace RDStation\Helpers;

use Psr\Http\Message\ResponseInterface;
use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\RequestFailed;
use GuzzleHttp\Client;

class Request
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    public function __construct(array $header)
    {
        $this->httpClient = new Client([
            'verify' => false,
            'connect_timeout' => 6,
            'timeout' => 0,
            'headers' => $header
        ]);
    }

    /**
     * @param $endpoint
     * @return array|mixed
     * @throws ContentTypeInvalid
     * @throws RequestFailed
     * @throws \JsonException
     */
    public function get($endpoint)
    {
        return $this->call('GET', $endpoint);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return mixed
     * @throws ContentTypeInvalid
     * @throws RequestFailed
     * @throws \JsonException
     */
    public function post($endpoint, array $data = [])
    {
        return $this->call('POST', $endpoint, ['json' => $data]);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return array|mixed
     * @throws RequestFailed
     * @throws \JsonException
     * @throws ContentTypeInvalid
     */
    public function put($endpoint, array $data = [])
    {
        return $this->call('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * @param $endpoint
     * @return array|mixed
     * @throws RDStation\Exception\ContentTypeInvalid
     * @throws RDStation\Exception\RequestFailed
     * @throws \JsonException
     * @throws ContentTypeInvalid
     * @throws RequestFailed
     */
    public function delete($endpoint)
    {
        return $this->call('DELETE', $endpoint);
    }


    /**
     * @param $method
     * @param $endpoint
     * @param array $options
     * @return mixed
     * @throws ContentTypeInvalid
     * @throws RequestFailed
     * @throws \JsonException
     */
    private function call($method, $endpoint, array $options = [])
    {
        $response = $this->httpClient->request($method, $endpoint, $options);
        $this->validateResponse($response);

        $content = $response->getBody()->getContents();
        $result = json_decode($content, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \JsonException();
        }

        return $result;
    }

    /**
     * @param ResponseInterface $response
     * @throws ContentTypeInvalid
     * @throws RequestFailed
     */
    private function validateResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() <= 200 && $response->getStatusCode() >= 299) {
            throw new RequestFailed();
        }

        if ($response->getHeader('content-type') !== 'application/json') {
            throw new ContentTypeInvalid();
        }
    }
}