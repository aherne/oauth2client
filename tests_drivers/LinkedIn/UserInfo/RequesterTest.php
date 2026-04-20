<?php
namespace Test\Lucinda\OAuth2\Vendor\LinkedIn\UserInfo;

use Lucinda\OAuth2\Server\Information;
use Lucinda\UnitTest\Validator\Arrays;
use Test\Lucinda\OAuth2\Support\Fixtures;
use Test\Lucinda\OAuth2\Support\JsonResponseWrapper;

class RequesterTest
{
    public function request()
    {
        $requester = new \Lucinda\OAuth2\Vendor\LinkedIn\UserInfo\Requester();
        $serverInformation = new Information([
            "authorization_url" => "https://example.com/authorize",
            "access_token_url" => "https://example.com/token",
            "scopes" => ["openid", "profile", "email"],
            "resource_url" => Fixtures::url("user-info/linkedin.json")
        ]);

        $response = $requester->request($serverInformation, new JsonResponseWrapper(), "access-token");
        return (new Arrays($response))->assertEquals([
            "sub" => "linkedin-1",
            "name" => "Jane LinkedIn",
            "email" => "jane@linkedin.example"
        ]);
    }
}
