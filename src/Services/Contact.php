<?php


namespace RDStation\Services;

use RDStation\Helpers\Request;
use RDStation\Request\Contact as ContactRequest;
use RDStation\Services\Treats\InstanceRequest;

class Contact
{

    use InstanceRequest;

    /** @var ContactRequest $contactRequest */
    protected $contactRequest;

    /** @var string $accessToken */
    protected $accessToken;

    public function __construct(ContactRequest $contact, string $accessToken)
    {
        $this->contactRequest = $contact;
        $this->accessToken = $accessToken;
    }

    public function create()
    {
        $request = $this->createRequest();
        $request->post();
    }

    public function update()
    {

    }

    protected function createRequest() : Request
    {
        return $this->getInstanceRequest([
            'Authorization' => $this->accessToken
        ]);
    }
}
