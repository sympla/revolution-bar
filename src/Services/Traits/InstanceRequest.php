<?php


namespace RDStation\Services\Traits;


use GuzzleHttp\Client;
use RDStation\Helpers\Request;

trait InstanceRequest
{
    /**
     *
     * @param array $headers
     * @return Request;
     */
    protected function getInstanceRequest(array $headers = []) : Request
    {
        return new Request(
            new Client([
                'verify' => false,
                'connect_timeout' => 6,
                'timeout' => 0,
                'headers' => $headers
            ])
        );
    }
}