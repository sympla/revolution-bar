<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RDStation\Configuration\Http;
use RDStation\Exception\JsonException as ExceptionJsonException;

class JsonException extends TestCase 
{

    public function testExceptionWithParameters()
    {
        $expectedMessage = "bad format json";
        $expectedCode    = 122;

        $jsonException   = new ExceptionJsonException($expectedMessage, $expectedCode);
        $this->assertEquals($expectedMessage, $jsonException->getMessage());
        $this->assertEquals($expectedCode, $jsonException->getCode());
    }

    public function testExceptionWithoutParameters()
    {
        $expectedMessage = "No error";
        $expectedCode    = 0;

        $jsonException   = new ExceptionJsonException();
        $this->assertEquals($expectedMessage, $jsonException->getMessage());
        $this->assertEquals($expectedCode, $jsonException->getCode());
    }


}