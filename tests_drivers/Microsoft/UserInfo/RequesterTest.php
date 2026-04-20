<?php
namespace Test\Lucinda\OAuth2\Vendor\Microsoft\UserInfo;

use Lucinda\OAuth2\Server\Information;
use Lucinda\UnitTest\Validator\Arrays;
use Test\Lucinda\OAuth2\Support\Fixtures;
use Test\Lucinda\OAuth2\Support\JsonResponseWrapper;

class RequesterTest
{
    public function request()
    {
        $requester = new \Lucinda\OAuth2\Vendor\Microsoft\UserInfo\Requester();
        $serverInformation = new Information([
            "authorization_url" => "https://example.com/authorize",
            "access_token_url" => "https://example.com/token",
            "scopes" => ["openid", "profile", "email", "User.Read"],
            "resource_url" => Fixtures::url("user-info/microsoft.json")
        ]);

        $response = $requester->request($serverInformation, new JsonResponseWrapper(), "access-token");
        return (new Arrays($response))->assertEquals([
            "id" => "microsoft-1",
            "displayName" => "Jane Microsoft",
            "mail" => "jane@microsoft.example",
            "userPrincipalName" => "fallback@microsoft.example"
        ]);
    }
}
