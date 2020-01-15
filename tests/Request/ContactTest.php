<?php
declare(strict_types=1);

namespace Request;

use RDStation\Exception\IncorrectTypeException;
use RDStation\Request\Contact;
use PHPUnit\Framework\TestCase;
use RDStation\Request\ContactIdentifier;


class ContactTest extends TestCase
{

    public function testConstructSuccess()
    {
        $expectedInstanceClass = Contact::class;
        $extraFields = ['Evento' => "Planeta Brasil"];
        $this->assertInstanceOf($expectedInstanceClass, new Contact(ContactIdentifier::EMAIL, []));
        $this->assertInstanceOf($expectedInstanceClass, new Contact(ContactIdentifier::UUID, []));
        $this->assertInstanceOf($expectedInstanceClass, new Contact(ContactIdentifier::EMAIL, $extraFields));
        $this->assertInstanceOf($expectedInstanceClass, new Contact(ContactIdentifier::UUID, $extraFields));

        $reflectionContactIdentifier = new \ReflectionClass(ContactIdentifier::class);
        foreach ($reflectionContactIdentifier->getConstants() as $constantValue) {
            $this->assertInstanceOf($expectedInstanceClass, new Contact($constantValue, []));
        }
    }

    public function testConstructorFailed()
    {
        $this->expectException(IncorrectTypeException::class);
        new Contact('SYMPLA', []);
        new Contact('SYMPLA', ['EVENTO' => 'NETFLIX']);
    }

    public function testGetAndSetters()
    {
        $extraFields = ['EVENTO' => 'NETFLIX', 'DATA_HORA' => date('Y-m-d')];
        $contactRequestEmail = new Contact(ContactIdentifier::EMAIL, $extraFields);

        foreach ($this->getExpectedsValuesSuccess() as $name => $expectedValue) {
            call_user_func([$contactRequestEmail, 'set' . $this->normalizeNameMethod($name)], $expectedValue);
            $this->assertEquals(
                $expectedValue,
                call_user_func([$contactRequestEmail, 'get' . $this->normalizeNameMethod($name)], [])
            );
        }

        $this->assertEquals($extraFields, $contactRequestEmail->getExtraFields());
        $this->assertEquals(ContactIdentifier::EMAIL, $contactRequestEmail->getIdentifier());

        $this->assertEquals(
            array_merge($this->getExpectedsValuesSuccess(), $extraFields),
            $contactRequestEmail->toArray()
        );

        $contactRequestEmail->setEmail('sympla+123@sympla.com');
    }

    public function testGetAndSettersFailed(): array
    {
        $extraFields = ['EVENTO' => 'NETFLIX', 'DATA_HORA' => date('Y-m-d')];
        $contactRequestEmail = new Contact(ContactIdentifier::EMAIL, $extraFields);


        $this->expectException(IncorrectTypeException::class);
        $contactRequestEmail->setWebsite("sympla");
        $contactRequestEmail->setEmail('sympla@sk');
        $contactRequestEmail->setEmail('sympla');
        $contactRequestEmail->setExtraEmails(['sympla', 'sympla@sls.com']);
    }

    private function getExpectedsValuesSuccess(): array
    {
        return [
            'uuid'  => 'uuid-123',
            'name'  => 'phpunit teste',
            'email' => 'phpunit@sympla.com',
            'job_title' => 'Dev',
            'bio' => 'bio-123',
            'website' => 'https://sympla.com.br',
            'linkedin' => 'sympla',
            'personal_phone' => '55 31 98020-9050',
            'city' => 'Belo Horizonte',
            'state' => 'Minas Gerais',
            'country' => 'Brasil',
            'tags' => ['evento', 'show'],
            'extra_emails' => ['sympla@sympla.com', 'teste@sympla.com']
        ];
    }

    public function testEmailValidateSuccess()
    {
        $class = $this->getInstanceClassWithEmailValidatePublic();

        $this->assertNull($class->emailValidate('sympla@sympla.com'));
        $this->assertNull($class->emailValidate('sympla+123@sympla.com'));
        $this->assertNull($class->emailValidate('sympla.sympla@sympla.com'));
        $this->assertNull($class->emailValidate('sympla.sympla@sympla.com.br'));
    }

    public function testEmailValidateFailed()
    {
        $class = $this->getInstanceClassWithEmailValidatePublic();

        $this->expectException(IncorrectTypeException::class);
        $class->emailValidate('sympla@di');
    }

    public function testIdentifierEmailAndEmailUninformed()
    {
        $this->expectException(\Exception::class);

        $contact = new Contact(ContactIdentifier::EMAIL, []);
        $contact->toArray();
    }


    public function testIdentifierUuidAndEmailUninformed()
    {
        $this->expectException(\Exception::class);

        $contact = new Contact(ContactIdentifier::UUID, []);
        $contact->toArray();
    }

    private function normalizeNameMethod($name): string
    {
        return ucwords(str_replace("_", "", $name));
    }

    private function getInstanceClassWithEmailValidatePublic()
    {
        return new class(ContactIdentifier::EMAIL, []) extends Contact {
            public function emailValidate(string $email)
            {
                parent::emailValidate($email);
            }
        };
    }


}
