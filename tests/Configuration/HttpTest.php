<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RDStation\Configuration\Http;

class HttpTest extends TestCase 
{
    public function testExistsConstants()
    {
        $expected = [
            'BASE_URL' => "https://api.rd.services"
        ];

        $reflectionHttp = new ReflectionClass(Http::class);
        $this->assertEquals($expected, $reflectionHttp->getConstants());
    }
}