<?php

require_once dirname(__FILE__) . '/Trait/MockRequest.php';

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use RDStation\Response\AuthorizationResponse;
use RDStation\Services\RefreshToken;
use PHPUnit\Framework\TestCase;

class RefreshTokenTest extends TestCase
{
    use MockRequest;

    public function testConstruct()
    {
        $expectedClass = RefreshToken::class;
        $this->assertInstanceOf($expectedClass,  new RefreshToken("alfkjsf", "slfkjs", "sflkjaslfk"));
    }

    public function testConstructFailed()
    {
        $this->expectException(\TypeError::class);
        new RefreshToken("alfkjsf", "slfkjs");
        new RefreshToken("alfkjsf");
        new RefreshToken();
        new RefreshToken("alfkjsf", "slfkjs", 123);
        new RefreshToken("alfkjsf", true, "123");
        new RefreshToken(1.6, "true", "123");
    }

    public function testParameters()
    {
        $refreshToken = $this->getInstanceRefreshTokenAllMethodsPublics();
        $expectedParameters = [
            'client_id' => "client_id",
            'client_secret' => "client_secret",
            'refresh_token' => "refresh_token"
        ];

        $this->assertEquals($expectedParameters, $refreshToken->getParameters());
    }

    public function testExecuteSuccess()
    {
        $expectAccessToken  = "client_id";
        $expectRefreshToken = "client_secret";
        $expectExpireIn = 8403123;

        /** @var RefreshToken|MockObject $refreshToken */
        $refreshToken = $this->getMockRefreshTokenTest();
        $request = $this->getMockRequestPost($expectAccessToken, $expectRefreshToken, $expectExpireIn);
        $refreshToken->method('getInstanceRequest')
            ->willReturn($request);

        /** @var AuthorizationResponse $accessToken */
        $accessToken = $refreshToken->execute();

        $this->assertInstanceOf(AuthorizationResponse::class, $accessToken);
        $this->assertEquals($expectAccessToken, $accessToken->getAccessToken());
        $this->assertEquals($expectRefreshToken, $accessToken->getRefreshToken());
        $this->assertEquals($expectExpireIn, $accessToken->getExpireIn());
    }

    private function getInstanceRefreshTokenAllMethodsPublics()
    {
        return new class("client_id", "client_secret", "refresh_token") extends RefreshToken {
            public function __call($name, $arguments)
            {
                return call_user_func_array([static::class, $name], $arguments);
            }
        };
    }

    private function getMockRefreshTokenTest(array $mockMethods = [])
    {
        $mockMethods = $mockMethods ?: ['getInstanceRequest'];
        return $this->getMockBuilder(RefreshToken::class)
            ->setConstructorArgs([
                'clientId' => '2498724',
                'clientSecret' => '24987242',
                'refreshToken' => '29847298472'
            ])
            ->enableOriginalConstructor()
            ->setMethods($mockMethods)
            ->getMock();
    }
}
