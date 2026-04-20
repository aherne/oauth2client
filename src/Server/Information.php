<?php

namespace Lucinda\OAuth2\Server;

class Information
{
    private string $authorizationUrl;
    private string $accessTokenUrl;
    private array $scopes;
    private string|array $resourceURL;
    private array $resourceFields;
    
    public function __construct(array $info)
    {
        $this->authorizationUrl = $info["authorization_url"];
        $this->accessTokenUrl = $info["access_token_url"];
        $this->scopes = $info["scopes"];
        $this->resourceURL = $info["resource_url"];
        $this->resourceFields = $info["resource_fields"]??[];
    }

    public function getAuthorizationUrl(): string
    {
        return $this->authorizationUrl;
    }

    public function getAccessTokenUrl(): string
    {
        return $this->accessTokenUrl;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function getResourceURL(): string|array
    {
        return $this->resourceURL;
    }

    public function getResourceFields(): array
    {
        return $this->resourceFields;
    }
}