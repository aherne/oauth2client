<?php
namespace Test\Lucinda\OAuth2\Vendor\Facebook\UserInfo;

use Lucinda\OAuth2\Server\Information;
use Lucinda\UnitTest\Validator\Arrays;
use Test\Lucinda\OAuth2\Support\Fixtures;
use Test\Lucinda\OAuth2\Support\JsonResponseWrapper;

class RequesterTest
{
    public function request()
    {
        $requester = new \Lucinda\OAuth2\Vendor\Facebook\UserInfo\Requester();
        $serverInformation = new Information([
            "authorization_url" => "https://example.com/authorize",
            "access_token_url" => "https://example.com/token",
            "scopes" => ["public_profile", "email"],
            "resource_url" => Fixtures::url("user-info/facebook.json"),
            "resource_fields" => ["id", "name", "email"]
        ]);

        $response = $requester->request($serverInformation, new JsonResponseWrapper(), "access-token");
        return (new Arrays($response))->assertEquals([
            "id" => "facebook-1",
            "name" => "Jane Facebook",
            "email" => "jane@facebook.example"
        ]);
    }
}
