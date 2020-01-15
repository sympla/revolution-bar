<?php

namespace RDStation\Services;

use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\IncorrectTypeException;
use RDStation\Exception\InvalidRouteException;
use RDStation\Exception\JsonException;
use RDStation\Exception\RequestFailed;
use RDStation\Helpers\Request;
use RDStation\Request\ContactRequest as ContactRequest;
use RDStation\Request\ContactIdentifier;
use RDStation\Services\Traits\InstanceRequest;

class Contact
{
    use InstanceRequest;

    /**
     * @var ContactRequest $contactRequest
     */
    protected $contactRequest;

    /**
     * @var string $accessToken
     */
    protected $accessToken;

    /**
     * Contact constructor.
     * @param ContactRequest $contact
     * @param string $accessToken
     */
    public function __construct(ContactRequest $contact, string $accessToken)
    {
        $this->contactRequest = $contact;
        $this->accessToken = $accessToken;
    }

    /**
     * @return array
     * @throws ContentTypeInvalid
     * @throws IncorrectTypeException
     * @throws InvalidRouteException
     * @throws JsonException
     * @throws RequestFailed
     */
    public function save()
    {
        $request = $this->createRequest();
        return $request->patch(
            $this->getUrlContactUpsert(),
            $this->contactRequest->toArray()
        );
    }

    /**
     * @return string
     * @throws IncorrectTypeException
     * @throws InvalidRouteException
     * @throws \ReflectionException
     */
    protected function getUrlContactUpsert()
    {
        return ContactIdentifier::getContactUrl($this->contactRequest->getIdentifier(), $this->getIdentifierValue());
    }

    /**
     * @return string
     * @throws IncorrectTypeException
     */
    protected function getIdentifierValue(): string
    {
        $identifiers = [
            ContactIdentifier::EMAIL => $this->contactRequest->getEmail(),
            ContactIdentifier::UUID  => $this->contactRequest->getUuid(),
        ];

        if (!$identifiers[$this->contactRequest->getIdentifier()]) {
            throw new IncorrectTypeException("Value identifier not found.");
        }

        return $identifiers[$this->contactRequest->getIdentifier()];
    }

    /**
     * @return Request
     */
    protected function createRequest(): Request
    {
        return $this->getInstanceRequest([
            'Authorization' => $this->accessToken
        ]);
    }
}
