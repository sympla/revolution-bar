<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RDStation\Configuration\Routes;

class RouteTest extends TestCase 
{
    public function testExistsConstants()
    {
        $expected = [
            'AUTHENTICATION' => "/auth/dialog",
            'AUTHORIZATION'  => '/auth/token',
            'CONTACT'        => '/platform/contacts',
            'REVOKING_TOKEN' => '/auth/revoke'
        ];

        $reflectionHttp = new ReflectionClass(Routes::class);
        $this->assertEquals($expected, $reflectionHttp->getConstants());
    }
}
