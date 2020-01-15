<?php

namespace Request;

use RDStation\Configuration\Routes;
use RDStation\Exception\IncorrectTypeException;
use RDStation\Helpers\BuildUrl;
use RDStation\Request\ContactIdentifier;
use PHPUnit\Framework\TestCase;

class ContactIdentifierTest extends TestCase
{

    public function testGetIdentifiersValid()
    {
        $expectedIdentifierValid = ['email', 'uuid'];
        $identifierValid = ContactIdentifier::getIdentifiersValid();
        $this->assertContains($expectedIdentifierValid[0], $identifierValid);
        $this->assertContains($expectedIdentifierValid[1], $identifierValid);
        $this->assertCount(count($expectedIdentifierValid), $identifierValid);

    }

    public function testGetContactUrl()
    {
        $expectedUrlEmail = BuildUrl::getUrlByRoute(Routes::CONTACT) . '/email:sympla@sympla.com';
        $this->assertEquals($expectedUrlEmail, ContactIdentifier::getContactUrl(ContactIdentifier::EMAIL, 'sympla@sympla.com'));

        $expectedUrlUuid = BuildUrl::getUrlByRoute(Routes::CONTACT) . '/uuid:uuid-123';
        $this->assertEquals($expectedUrlUuid, ContactIdentifier::getContactUrl(ContactIdentifier::UUID, 'uuid-123'));
    }

    public function testGetContactUrlFailed()
    {
        $this->expectException(IncorrectTypeException::class);
        ContactIdentifier::getContactUrl('sympla', 'sympla@sympla.com');

        $this->expectException(TypeError::class);
        ContactIdentifier::getContactUrl(123, 'slls');
    }

    public function testConstantsExists()
    {
        $reflectionConstants = new \ReflectionClass(ContactIdentifier::class);
        $constants = $reflectionConstants->getConstants();
        $this->assertCount(2, $constants);
        $this->assertNotEmpty($constants['EMAIL']);
        $this->assertNotEmpty($constants['UUID']);
    }
}
