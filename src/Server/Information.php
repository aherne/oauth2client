<?php

namespace Lucinda\OAuth2\Server;

/**
 * Encapsulates information about OAuth2 provider endpoints.
 */
class Information
{
    private string $authorizationEndpoint;
    private string $tokenEndpoint;

    /**
     * Creates an object
     *
     * @param string $authorizationEndpoint URL to provider's authorization code service.
     * @param string $tokenEndpoint         URL to provider's access token service.
     */
    public function __construct(string $authorizationEndpoint, string $tokenEndpoint)
    {
        $this->authorizationEndpoint = $authorizationEndpoint;
        $this->tokenEndpoint = $tokenEndpoint;
    }

    /**
     * Gets authorization code endpoint URL
     *
     * @return string
     */
    public function getAuthorizationEndpoint(): string
    {
        return $this->authorizationEndpoint;
    }

    /**
     * Get access token endpoint URL
     *
     * @return string
     */
    public function getTokenEndpoint(): string
    {
        return $this->tokenEndpoint;
    }
}
