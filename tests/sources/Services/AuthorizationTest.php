<?php

declare(strict_types=1);

use PHPUnit\Framework\MockObject\MockObject;
use RDStation\Services\Authorization;
use PHPUnit\Framework\TestCase;
use RDStation\Helpers\Request;
use RDStation\Response\AuthorizationResponse;

class AuthorizationTest extends TestCase
{

    public function testContructorSuccess()
    {
        $this->assertInstanceOf(Authorization::class, new Authorization("1234", "abc", "aaaabbb"));
    }

    public function testContructorError()
    {
        $this->expectException(ArgumentCountError::class);
        new Authorization("1234", "abc");
        new Authorization("1234");
        new Authorization();

        $this->expectException(TypeError::class);
        new Authorization("1234", "abc", true);
        new Authorization("1234", 123, "sflks");
        new Authorization(9999, 123, "sflks");
    }

    public function testGetAccessToken()
    {

        $expectedAccessToken = '24982749824';
        $expectedRefreshToken = 'flkskljr948274';
        $expectedExpireIn = '28472984728472';

        $returnRequest = [
            'access_token' => $expectedAccessToken,
            'refresh_token' => $expectedRefreshToken,
            'expires_in' => $expectedExpireIn
        ];

        $request = $this->getMockRequest($returnRequest);

        $request->method('post')
            ->willReturn([
            'access_token' => $expectedAccessToken,
            'refresh_token' => $expectedRefreshToken,
            'expires_in' => $expectedExpireIn
        ]);

        /** @var Authorization|MockObject $authorization */
        $authorization = $this->getMockAuthorizationTest();
        $authorization->method('getInstanceRequest')
            ->willReturn($request);

        
        /** @var AuthorizationResponse $accessToken */
        $accessToken = $authorization->getAccessToken();

        $this->assertInstanceOf(AuthorizationResponse::class, $accessToken);
        $this->assertEquals($expectedAccessToken, $accessToken->getAccessToken());
        $this->assertEquals($expectedRefreshToken, $accessToken->getRefreshToken());
        $this->assertEquals($expectedExpireIn, $accessToken->getExpireIn());
    }
    
    private function getMockAuthorizationTest()
    {
        $mockAuthorization = $this->getMockBuilder(Authorization::class)
                ->setConstructorArgs([
                    'clientId' => '2498724',
                    'clientSecret' => '24987242',
                    'code' => '29847298472'
                ])
                ->enableOriginalConstructor()
                ->setMethods(['getInstanceRequest'])
                ->getMock();         
                
        return $mockAuthorization;
    }

    private function getMockRequest(array $return)
    {

        return $this->createMock(Request::class);        
    }

}
