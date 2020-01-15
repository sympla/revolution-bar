<?php
require_once dirname(__FILE__) . '/Trait/MockRequest.php';

use PHPUnit\Framework\MockObject\MockObject;
use RDStation\Services\Authorization;
use PHPUnit\Framework\TestCase;
use RDStation\Configuration\Routes;
use RDStation\Helpers\BuildUrl;
use RDStation\Helpers\Request;
use RDStation\Response\AuthorizationResponse;

class AuthorizationTest extends TestCase
{
    use MockRequest;

    public function testContructorSuccess()
    {
        $this->assertInstanceOf(Authorization::class, new Authorization("1234", "abc", "aaaabbb"));
    }

    public function testContructorError()
    {
        $this->expectException(TypeError::class);
        new Authorization("1234", "abc");
        new Authorization("1234");
        new Authorization();

        new Authorization("1234", "abc", true);
        new Authorization("1234", 123, "sflks");
        new Authorization(9999, 123, "sflks");
    }

    public function testGetAccessToken()
    {

        $expectedAccessToken = '24982749824';
        $expectedRefreshToken = 'flkskljr948274';
        $expectedExpireIn = '28472984728472';
        $request = $this->getMockRequestPost($expectedAccessToken, $expectedRefreshToken, $expectedExpireIn);

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

    public function testMethodsProtectedAreCalled()
    {
        $mockMethods = [
            "getInstanceRequest",
            "getUrlAuthorization",
            "getParameters",
            "generateAuthorizationResponse"
        ];

        /** @var Authorization|MockObject $authorization */
        $authorization = $this->getMockAuthorizationTest($mockMethods);
        $request = $this->getMockRequest();        
        $request->method('post')
            ->willReturn([
                'access_token' => 'sfs',
                'refresh_token' => '24',
                'expires_in' => '242'
            ]);

        $authorization->expects($this->once())
            ->method('getInstanceRequest')
            ->willReturn($request);

        $authorization->expects($this->once())
            ->method("getUrlAuthorization");

        $authorization->expects($this->once())
            ->method("getParameters");
        
        $authorization->getAccessToken();
    }


    public function testeGetParameters()
    {
        $expectedClientId = "29342";
        $expectedClientSecret = "28479284";
        $expectedCode = "soiwurowir";
        
        $authorization = $this->getInstanceAuthorizationAccessAllMethods($expectedClientId, $expectedClientSecret, $expectedCode);
        $parameters = $authorization->getParameters();
        $this->assertNotEmpty($parameters['client_id']);
        $this->assertNotEmpty($parameters['client_secret']);
        $this->assertNotEmpty($parameters['code']);
        $this->assertEquals($expectedClientId, $parameters['client_id']);
        $this->assertEquals($expectedClientSecret, $parameters['client_secret']);
        $this->assertEquals($expectedCode, $parameters['code']);
    }

    public function testGetInstanceRequest()
    {
        $authorization = $this->getInstanceAuthorizationAccessAllMethods("12489", "2984", "wiury");
        $this->assertInstanceOf(Request::class, $authorization->getInstanceRequest());
    }

    public function testGetUrlBuilder()
    {
        $authorization = $this->getInstanceAuthorizationAccessAllMethods("12489", "2984", "wiury");
        $expectedUrl = BuildUrl::getUrlByRoute(Routes::AUTHORIZATION);
        $this->assertEquals($expectedUrl, $authorization->getUrlAuthorization());
    }

    protected function getMockAuthorizationTest(array $mockMethods = [])
    {

        $mockMethods = $mockMethods ?: ['getInstanceRequest'];
        $mockAuthorization = $this->getMockBuilder(Authorization::class)
                ->setConstructorArgs([
                    'client' => '2498724',
                    'clientSecret' => '24987242',
                    'code' => '29847298472'
                ])
                ->enableOriginalConstructor()
                ->setMethods($mockMethods)
                ->getMock();

        return $mockAuthorization;
    }


    /**
     * @method getParameters()
     */
    private function getInstanceAuthorizationAccessAllMethods($clientId, $clientSecret, $clientCode) : Authorization
    {
        return new class($clientId, $clientSecret, $clientCode) extends Authorization {
            public function __call($name, $arguments)
            {
                return call_user_func_array([static::class, $name], $arguments);
            }
        };
    }

}
