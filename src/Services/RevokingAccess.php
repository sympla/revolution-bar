<?php

namespace RDStation\Services;

use RDStation\Configuration\Routes;
use RDStation\Exception\ContentTypeInvalid;
use RDStation\Exception\InvalidRouteException;
use RDStation\Exception\JsonException;
use RDStation\Exception\RequestFailed;
use RDStation\Helpers\BuildUrl;
use RDStation\Services\Traits\InstanceRequest;
use ReflectionException;

class RevokingAccess
{
    use InstanceRequest;

    const VALIDS_TYPE_HINT = [
        'access_token',
        'refresh_token'
    ];

    /** @var string $accessToken */
    protected $accessToken;

    /** @var string $token */
    protected $refreshToken;

    /** @var string $tokenTypeHint */
    protected $tokenTypeHint;

    /**
     * RevokingAccess constructor.
     * @param string $accessToken
     * @param string $refreshToken
     * @param string|null $tokenTypeHint [OPTIONAL "access_token" or "refresh_token" indicate the type of token provided ]
     */
    public function __construct(string $accessToken, string $refreshToken = null, string $tokenTypeHint = null)
    {
        if (! $this->validateTypeHint($tokenTypeHint)) {
            throw new \InvalidArgumentException();
        }

        $this->accessToken   = $accessToken;
        $this->refreshToken  = $refreshToken;
        $this->tokenTypeHint = $tokenTypeHint;
    }

    /**
     * @return array|mixed
     * @throws InvalidRouteException
     * @throws ReflectionException
     * @throws ContentTypeInvalid
     * @throws JsonException
     * @throws RequestFailed
     */
    public function revoke()
    {
        $request = $this->getInstanceRequest([
            "Authorization" => "Bearer " . $this->accessToken
        ]);

        return $request->post(
            $this->getEndpoint(),
            $this->getParameters()
        );
    }

    /**
     * @return string
     * @throws InvalidRouteException
     * @throws ReflectionException
     */
    protected function getEndpoint(): string
    {
        return BuildUrl::getUrlByRoute(Routes::REVOKING_TOKEN);
    }


    /**
     * Return parameters to request
     * @return array
     */
    protected function getParameters(): array
    {
        $token = $this->refreshToken && $this->tokenTypeHint == "refresh_token"
            ? $this->refreshToken
            : $this->accessToken;

        return array_filter([
            "token" => $token,
            "token_type_hint" => $this->tokenTypeHint
        ]);
    }

    protected function validateTypeHint(string $typeHint = null): bool
    {
        if (is_null($typeHint) || in_array($typeHint, self::VALIDS_TYPE_HINT)) {
            return true;
        }

        return false;
    }
}
