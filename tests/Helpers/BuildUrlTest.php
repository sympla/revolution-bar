<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RDStation\Configuration\Routes;
use RDStation\Exception\InvalidRouteException;
use RDStation\Helpers\BuildUrl;

class BuildUrlTest extends TestCase
{

    public function testGetUrlByRouteSuccess()
    {
        $expectedUrlAuthentication = "https://api.rd.services/auth/dialog";
        $expectedUrlAuthorization  = "https://api.rd.services/auth/token";
        $expectedUrlRevokeToken    =  "https://api.rd.services/auth/revoke";
        $expectedUrlContact        =  "https://api.rd.services/platform/contacts";

        $this->assertEquals($expectedUrlAuthentication, BuildUrl::getUrlByRoute(Routes::AUTHENTICATION));
        $this->assertEquals($expectedUrlAuthorization, BuildUrl::getUrlByRoute(Routes::AUTHORIZATION));
        $this->assertEquals($expectedUrlRevokeToken, BuildUrl::getUrlByRoute(Routes::REVOKING_TOKEN));
        $this->assertEquals($expectedUrlContact, BuildUrl::getUrlByRoute(Routes::CONTACT));
    }

    public function testGetUrlByRouteWithRouteInvalidRoute()
    {
        $this->expectException(InvalidRouteException::class);

        BuildUrl::getUrlByRoute("google");
    }

    public function testGetUrlByRouteWithoutParameters()
    {
        $this->expectException(TypeError::class);
        BuildUrl::getUrlByRoute();
    }

    public function testGetUrlByRouteParameterWrong()
    {
        $this->expectException(TypeError::class);
        BuildUrl::getUrlByRoute(true);
    }


    public function testGetUrlByRouteParameterNull()
    {
        $this->expectException(TypeError::class);
        BuildUrl::getUrlByRoute(null);
    }
}
