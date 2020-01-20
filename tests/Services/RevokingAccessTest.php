<?php
declare(strict_types=1);
require_once dirname(__FILE__) . '/Trait/MockRequest.php';


use RDStation\Configuration\Routes as Route;
use RDStation\Helpers\BuildUrl;
use RDStation\Helpers\Request;
use RDStation\Services\RevokingAccess;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;


class RevokingAccessTest extends TestCase
{
    use MockRequest;

    public function testConstruct()
    {
        $this->assertInstanceOf(RevokingAccess::class, new RevokingAccess("token"));
        $this->assertInstanceOf(RevokingAccess::class, new RevokingAccess("token", "refresh_token"));
        $this->assertInstanceOf(RevokingAccess::class, new RevokingAccess("token", "refresh_token", "refresh_token"));
        $this->assertInstanceOf(RevokingAccess::class, new RevokingAccess("token", "refresh_token", "access_token"));
    }

    public function testConstructFailed()
    {
        $this->expectException(TypeError::class);
        new RevokingAccess(1);
        new RevokingAccess("true", 1);
        new RevokingAccess("true", "1", 1);
    }

    public function testConstructInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);
        new RevokingAccess("true", "1", "sympla");
    }

    public function testSuccessRevoke()
    {
        /** @var MockObject|Request $mockRequest */
        $mockRequest = $this->getMockRequest();
        $mockRequest->expects($this->once())
            ->method('post')
            ->willReturn([]);

        /** @var MockObject|RevokingAccess $mockRevokeAccess */
        $mockRevokeAccess = $this->getMockRevokeAccess("access");
        $mockRevokeAccess->expects($this->once())
            ->method('getInstanceRequest')
            ->willReturn($mockRequest);

        $this->assertEquals([], $mockRevokeAccess->revoke());
    }

    public function testGetEndpoint()
    {
        $revoking = $this->getInstanceRevokeTokenAllMethodsPublics("access", "refresh", "access_token");
        $expectedUrl = BuildUrl::getUrlByRoute(Route::REVOKING_TOKEN);
        $this->assertEquals($expectedUrl, $revoking->getEndpoint());
    }

    public function testGetParametersAccessToken()
    {
        $expectedParameters = [
            "token" => "token_expected",
            "token_type_hint" => RevokingAccess::VALIDS_TYPE_HINT[0]
        ];


        $revoking = $this->getInstanceRevokeTokenAllMethodsPublics(
            $expectedParameters['token'],
            null,
            $expectedParameters["token_type_hint"]
        );

        $this->assertEquals($expectedParameters, $revoking->getParameters());


        $revoking = $this->getInstanceRevokeTokenAllMethodsPublics(
            $expectedParameters['token'],
            "1234242",
            $expectedParameters["token_type_hint"]
        );
        $this->assertEquals($expectedParameters, $revoking->getParameters());
    }

    public function testGetParametersRefreshToken()
    {
        $expectedParameters = [
            "token" => "refresh_token",
            "token_type_hint" => RevokingAccess::VALIDS_TYPE_HINT[1]
        ];

        $revoking = $this->getInstanceRevokeTokenAllMethodsPublics(
            "sfsfsfsfsf",
            $expectedParameters["token"],
            $expectedParameters["token_type_hint"]
        );
        $this->assertEquals($expectedParameters, $revoking->getParameters());
    }

    public function testValidateTypeHint()
    {
        $revoking = $this->getInstanceRevokeTokenAllMethodsPublics('token', "1234242");
        $this->assertFalse($revoking->validateTypeHint("sympla"));
        $this->assertTrue($revoking->validateTypeHint(RevokingAccess::VALIDS_TYPE_HINT[0]));
        $this->assertTrue($revoking->validateTypeHint(RevokingAccess::VALIDS_TYPE_HINT[1]));
    }

    private function getMockRevokeAccess(string $accessToken, string $refreshToken = null, string $tokenTypeHint = null)
    {
        return $this->getMockBuilder(RevokingAccess::class)
            ->setConstructorArgs([
                'accessToken'   => $accessToken,
                'refreshToken'  => $refreshToken,
                'tokenTypeHint' => $tokenTypeHint
            ])
            ->enableOriginalConstructor()
            ->setMethods(['getInstanceRequest'])
            ->getMock();
    }

    private function getInstanceRevokeTokenAllMethodsPublics($accessToken, $refreshToken, $tokenTypeHint = null)
    {
        return new class($accessToken, $refreshToken, $tokenTypeHint) extends RevokingAccess {
            public function __call($name, $arguments)
            {
                return call_user_func_array([static::class, $name], $arguments);
            }

            public function __get($name)
            {
                return $this->{$name};
            }

            public function __set($name, $value)
            {
                $this->{$name} = $value;
            }
        };
    }
}
