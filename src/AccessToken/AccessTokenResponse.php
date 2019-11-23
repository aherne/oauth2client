<?php
namespace Lucinda\OAuth2\AccessToken;


/**
 * Encapsulates a successful access token response according to RFC6749
 */
class AccessTokenResponse
{
    protected $accessToken;
    protected $tokenType;
    protected $expiresIn;
    protected $refreshToken;
    protected $scope;
    
    /**
     * Populates response based on parameter keys defined in RFC6749
     *
     * @param string[string] $parameters Access token response parameters received
     */
    public function __construct(array $parameters): void
    {
        $this->accessToken = $parameters["access_token"];
        if (!empty($parameters["token_type"])) {
            $this->tokenType = "Bearer";
        }
        if (!empty($parameters["expires_in"])) {
            $this->expiresIn = $parameters["expires_in"];
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
     * @return string
     */
    public function getExpiresIn(): string
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
