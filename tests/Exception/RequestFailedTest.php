<?php

namespace Exception;

use GuzzleHttp\Psr7\Response;
use RDStation\Exception\RequestFailed;
use PHPUnit\Framework\TestCase;

class RequestFailedTest extends TestCase
{

    public function testGetResponse()
    {
        $response = new Response(400, [], "Bad Request");
        $requestFailed = new RequestFailed("Bad Request", $response->getStatusCode(), $response);

        $this->assertEquals($response, $requestFailed->getResponse());
    }

    public function testGetStatusCode()
    {
        $response = new Response(400, [], "Bad Request");
        $requestFailed = new RequestFailed("Bad Request", $response->getStatusCode(), $response);

        $this->assertEquals($response->getStatusCode(), $requestFailed->getStatusCode());
    }

    public function test__construct()
    {
        $expectedClass = RequestFailed::class;
        $response = new Response(400, [], "Bad Request");
        $requestFailed = new RequestFailed("Bad Request", $response->getStatusCode(), $response);

        $this->assertInstanceOf($expectedClass, $requestFailed);
    }
}
