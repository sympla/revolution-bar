<?php

namespace RDStation\Response;

class AuthorizationResponse
{

    /**
     * @var string $accessToken
     */
    private $accessToken;

    /**
     * @var string $refreshToken
     */
    private $refreshToken;

    /**
     * @var int $expireIn
     */
    private $expireIn;

    /**
     * AuthenticateResponse constructor.
     *
     * @param string $accessToken
     * @param string $refreshToken
     * @param int    $expireIn
     */
    public function __construct(string $accessToken, string $refreshToken, int $expireIn)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expireIn = $expireIn;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @return int
     */
    public function getExpireIn(): int
    {
        return $this->expireIn;
    }
}
