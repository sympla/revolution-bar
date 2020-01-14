<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RDStation\Configuration\Routes;
use RDStation\Helpers\BuildUrl;
use RDStation\Services\Authentication;

class AuthenticationTest extends TestCase
{

    public function testContructSuccess()
    {
        $this->assertInstanceOf(Authentication::class, new Authentication("abc", "http", []));
        $this->assertInstanceOf(Authentication::class, new Authentication("abc", "http"));
    }

    public function testConstructFailed()
    {
        $this->expectException(TypeError::class);
        new Authentication("af");

        new Authentication("af", 1);
        new Authentication("af", null);

        new Authentication(1, 1);
        new Authentication(null, null);

        new Authentication(true, "a");
        new Authentication("af", "ss", new stdClass());
    }

    public function testGetUrlAuthentication()
    {
        $executeTest = function($client_id, $redirect_url, $params = []) {
            $parametersUrl = compact('client_id', 'redirect_url', 'params');
            $expectedUrl = sprintf(
                    "%s?%s",
                    BuildUrl::getUrlByRoute(Routes::AUTHENTICATION),
                    http_build_query($parametersUrl)
            );
            $authentication = new Authentication($client_id, $redirect_url, $params);
            $this->assertEquals($expectedUrl, $authentication->getUrlAuthentication());

        };

        $parameters = [
            ['ab', "https://sympla.com.br"],
            ['ab', "https://sympla.com.br", ["param1" => "abc", "param2" => "sflks"]],
        ];

        foreach ($parameters as $param) {
            $executeTest($param[0], $param[1], $param[2] ?? []);
        }        
    }
}