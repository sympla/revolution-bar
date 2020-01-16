<?php

namespace RDStation\Services;

use RDStation\Services\Contract\AuthorizationAbstract;

class RefreshToken extends AuthorizationAbstract
{
    protected $refreshToken;

    /**
     * RefreshToken constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     */
    public function __construct(string $clientId, string $clientSecret, string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
        parent::__construct($clientId, $clientSecret);
    }

    /**
     * @see AuthorizationAbstract::getParameters()
     * @return array
     */
    protected function getParameters(): array
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken
        ];
    }
}
