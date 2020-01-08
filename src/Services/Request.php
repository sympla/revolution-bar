<?php

namespace RDStation\Services;

use Psr\Http\Message\ResponseInterface;

class Request
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;
    /**
     * @var bool
     */
    private $exceptionIfError;
    public function __construct(array $header)
    {
        $this->httpClient = new GuzzleHttp\Client([
            'verify' => false,
            'connect_timeout' => 6,
            'timeout' => 0,
            'headers' => $header
        ]);
    }

    /**
     * @param $endpoint
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($endpoint)
    {
        return $this->call('GET', $endpoint);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($endpoint, array $data = [])
    {
        return $this->call('POST', $endpoint, ['json' => $data]);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($endpoint, array $data = [])
    {
        return $this->call('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * @param $endpoint
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($endpoint)
    {
        return $this->call('DELETE', $endpoint);
    }

    /**
     * @param $method
     * @param $endpoint
     * @param array $options
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function call($method, $endpoint, array $options = [])
    {
        try {
            $request = $this->httpClient->request($method, $endpoint, $options);

            if ($request->getStatusCode() <= 200 && $request->getStatusCode() >= 299) {
                throw new Exception\RequestFailed();
            }

            $content = $request->getBody()->getContents();
            $result = json_decode($content, true);
            $this->printLn($result,true);
        } catch (\Exception $e) {
            $this->printLn($e->getMessage());
            $result = ['success' => false, 'error' => '1 - Erro ao processar ! ' . $e->getMessage()];
        }
        $this->printLn($result);
        return $result;
    }

    private function validateResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() <= 200 && $response->getStatusCode() >= 299) {
            throw new Exception\RequestFailed();
        }

        if ($response->getHeader('content-type') !== 'application/json') {
            throw new Exception\ContentTypeInvalid();
        }

    }

    private function printLn($content, $pre = false)
    {
        if($pre)
            echo "<pre>";
        print_r($content);
        exit;
    }
}