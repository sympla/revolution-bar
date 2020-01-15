<?php

namespace Services;

use PHPUnit\Framework\MockObject\MockBuilder;
use RDStation\Exception\IncorrectTypeException;
use RDStation\Helpers\Request;
use RDStation\Request\ContactIdentifier;
use RDStation\Services\Contact;
use PHPUnit\Framework\TestCase;
use RDStation\Request\Contact as ContactRequest;


class ContactTest extends TestCase
{

    public function testConstruct()
    {
        $expectedInstance = Contact::class;
        $contactRequest = new ContactRequest(ContactIdentifier::EMAIL, []);
        $this->assertInstanceOf($expectedInstance, new Contact($contactRequest, "afklsjfs"));
    }

    public function testSaveSuccess()
    {
        /** @var Contact|MockBuilder $contact */
        $contact = $this->getMockInstanceContact();
        $contact->save();
    }

    public function testCreateRequest()
    {
        $contact = $this->getInstanceContactAllMethodsPublics(new ContactRequest(), "slkfjslfk");


        $this->assertInstanceOf(Request::class, $contact->createRequest());
    }

    public function testSaveIdentifierValueFailed()
    {
        $this->expectException(IncorrectTypeException::class);
        $this->expectExceptionMessage("Value identifier not found.");
        $mockRequest = $this->getMockBuilder(ContactRequest::class)
            ->disableOriginalConstructor()
            ->enableAutoload()
            ->getMock();

        $mockRequest->method('getIdentifier')
            ->willReturn("sympla");

        $contactRequest = new class() extends ContactRequest {

            public function getIdentifier(): string
            {
                return "sympla";
            }
        };


        $contact = new Contact($contactRequest, "afklsjfs");

        $contact->save();
    }

    private function getMockInstanceContact()
    {
        $mockContactTest = $this->getMockBuilder(Contact::class)
            ->setConstructorArgs([
                'contact' => $this->getInstanceContactIdentifierEmail(),
                'accessToken' => '29487294'
            ])
            ->enableOriginalConstructor()
            ->setMethods(['createRequest'])
            ->getMock();

        $mockRequest = $this->createMock(Request::class);
        $mockRequest
            ->expects($this->once())
            ->method('patch')
            ->willReturn([
                'name' => 'Sympla',
                'email' => 'sympla@sympla.com',
                'personal_phone' => '(31)980209050',
                'city' => 'Belo Horizonte',
                'state' => 'Minas Gerais',
                'country' => 'Brazil'
            ]);

        $mockContactTest->method('createRequest')
            ->willReturn($mockRequest);

        return $mockContactTest;
    }

    protected function getInstanceContactAllMethodsPublics(ContactRequest $contactRequest): Contact
    {
        return new class($contactRequest, "sfklsf") extends Contact {
            public function __call($name, $arguments)
            {
                return call_user_func_array([static::class, $name], $arguments);
            }
        };
    }

    private function getInstanceContactIdentifierEmail()
    {
        $contactIdentifier = new ContactRequest(ContactIdentifier::EMAIL, ['evento' => 'Netflix']);
        $contactIdentifier->setUuid('12424');
        $contactIdentifier->setName("Sympla");
        $contactIdentifier->setEmail("sympla@sympla.com");
        $contactIdentifier->setPersonalPhone("(31)980209050");
        $contactIdentifier->setCity("Belo Horizonte");
        $contactIdentifier->setState("Minas Gerais");
        $contactIdentifier->setCountry("Brasil");

        return $contactIdentifier;
    }
}
