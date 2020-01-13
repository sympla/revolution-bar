<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RDStation\Response\AuthorizationResponse;

class AuthenticateResponseTest extends TestCase
{

    /** @var AuthorizationResponse $authorizationResponse */
    private $authorizationResponse;

    public function setUp()
    {
        $this->authorizationResponse = new AuthorizationResponse("token", "refresh", 199);
        parent::setUp();
    }

    public function testContructExceptionWithoutExpireTime()
    {
        $this->expectException(TypeError::class);
        $response = new AuthorizationResponse("token", "refresh");
    }

    public function testContructErrorExpireTimeTypeWrong()
    {
        $this->expectException(TypeError::class);
        $response = new AuthorizationResponse("token", "refresh", "w");
    }

    public function testContructExpireTimeNull()
    {
        $this->expectException(TypeError::class);
        $response = new AuthorizationResponse("token", "refresh", null);
    }
    

    public function testContructRefreshTokenTypeWrong()
    {
        $this->expectException(TypeError::class);
        $response = new AuthorizationResponse("token", true, 200);
    }

    public function testContructRefreshTokenNull()
    {
        $this->expectException(TypeError::class);
        $response = new AuthorizationResponse("token", null, 400);
    }


    public function testContructTokenTypeWrong()
    {
        $this->expectException(TypeError::class);
        $response = new AuthorizationResponse(1451, "29847", 200);
    }

    public function testContructTokenNull()
    {
        $this->expectException(TypeError::class);
        $response = new AuthorizationResponse(null, "5522", 400);
    }

    public function testGetAccessToken()
    {
        $expectedAccessToken = "token";
        $this->assertEquals($expectedAccessToken, $this->authorizationResponse->getAccessToken());
    }

    public function testGetRefreshToken()
    {
        $expectedRefreshToken = "refresh";
        $this->assertEquals($expectedRefreshToken, $this->authorizationResponse->getRefreshToken());
    }

    public function testGetExpireIn()
    {
        $expectedGetExpireIn = 199;
        $this->assertEquals($expectedGetExpireIn, $this->authorizationResponse->getExpireIn());
    }
}
