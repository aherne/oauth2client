<?php

namespace Lucinda\OAuth2\AccessToken;

/**
 * Encapsulates a successful access token response according to RFC6749
 */
class Response
{
    protected string $accessToken;
    protected string $tokenType;
    protected int $expiresIn;
    protected string $refreshToken;
    protected string $scope;

    /**
     * Populates response based on parameter keys defined in RFC6749
     *
     * @param array<string,string|int> $parameters Access token response parameters received
     */
    public function __construct(array $parameters)
    {
        $this->accessToken = $parameters["access_token"];
        $this->tokenType = (!empty($parameters["token_type"]) ? $parameters["token_type"] : "Bearer");
        if (!empty($parameters["expires_in"])) {
            $this->expiresIn = time()+$parameters["expires_in"];
        }
        if (!empty($parameters["refresh_token"])) {
            $this->refreshToken = $parameters["refresh_token"];
        }
        if (!empty($parameters["scope"])) {
            $this->scope = $parameters["scope"];
        }
    }

    /**
     * Gets access token issued by the authorization server.
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Gets token type received by the authorization server to use for protected resource request.
     *
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * Gets access token lifetime (in seconds) issued by the authorization server.
     *
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * Gets refresh token used to obtain new access tokens using the same authorization grant
     *
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * Gets scope of token issued by the authorization server.
     *
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }
}
