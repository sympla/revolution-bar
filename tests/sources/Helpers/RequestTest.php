<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RDStation\Configuration\Routes;
use RDStation\Exception\InvalidRouteException;
use RDStation\Exception\RequestFailed;
use RDStation\Helpers\BuildUrl;
use RDStation\Helpers\Request;

class RequestTest extends TestCase
{
    /**
     * @return Client
     */
    private function getMockGuzzle()
    {    
 

        return $this->createMock(Client::class);
    }


    private function getInstanceRequest(Client $client = null)
    {
        if (is_null($client)) {
            $client = $this->getMockGuzzle();
        }  

        return new Request($client);
    }

    public function testContruct()
    {
        $this->assertInstanceOf(Request::class, $this->getInstanceRequest());        
    }

    public function testRequestGetSuccess()
    {

        $expectedReturn = [
            "foo" => "bar"
        ];

        /**
         * @var MockObject $mockGuzzle
         */
        $mockGuzzle = $this->getMockGuzzle();
        $response = new Response(200, [], json_encode($expectedReturn));
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $responseRequest = $request->get('https://sympla.com.br');
        $this->assertEquals($expectedReturn, $responseRequest);
    }

    public function testRequestGetRequestFailed()
    {

        $this->expectException(RequestFailed::class);
        /**
         * @var MockObject $mockGuzzle
         */
        $mockGuzzle = $this->getMockGuzzle();
        $response = new Response(404, [], "NOT FOUND");
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $responseRequest = $request->get('https://sympla.com.br');
    }

    public function testRequestGetJsonException()
    {

        $this->expectException(JsonException::class);
        /**
         * @var MockObject $mockGuzzle
         */
        $mockGuzzle = $this->getMockGuzzle();
        $response = new Response(200, [], "UM HTML QUALQUER");
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $responseRequest = $request->get('https://sympla.com.br');
    }

    public function testRequestPostSuccess()
    {
        $expectedReturn = [
            "foo" => "bar"
        ];

        /**
         * @var Client $mockGuzzle
         */
        $mockGuzzle = $this->getMockGuzzle();
        $response = new Response(200, [], json_encode($expectedReturn));
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $responseRequest = $request->post('https://sympla.com.br', ['param' => 2]);
        $this->assertEquals($expectedReturn, $responseRequest);
    }

    public function testRequestPostFailed()
    {

        $this->expectException(RequestFailed::class);

        /**
         * @var MockObject $mockGuzzle
         */
        $mockGuzzle = $this->getMockGuzzle();
        $response = new Response(404, [], "NOT FOUND");
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $request->post('https://sympla.com.br', []);

        $this->expectException(JsonException::class);
        $response = new Response(200, [], "HTML");
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $request->post('https://sympla.com.br', []);
    }

    public function testRequestPutSuccess()
    {
        $expectedReturn = [
            "foo" => "bar"
        ];

        /**
         * @var Client $mockGuzzle
         */
        $mockGuzzle = $this->getMockGuzzle();
        $response = new Response(200, [], json_encode($expectedReturn));
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $responseRequest = $request->put('https://sympla.com.br', ['param' => 2]);
        $this->assertEquals($expectedReturn, $responseRequest);
    }

    public function testRequestPutFailed()
    {

        $this->expectException(RequestFailed::class);

        /**
         * @var MockObject $mockGuzzle
         */
        $mockGuzzle = $this->getMockGuzzle();
        $response = new Response(404, [], "NOT FOUND");
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $request->put('https://sympla.com.br', []);

        $this->expectException(JsonException::class);
        $response = new Response(200, [], "HTML");
        $mockGuzzle->method('request')
            ->willReturn($response);

        $request = $this->getInstanceRequest($mockGuzzle);
        $request->put('https://sympla.com.br', []);
    }
}