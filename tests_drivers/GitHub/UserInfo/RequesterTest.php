<?php
namespace Test\Lucinda\OAuth2\Vendor\GitHub\UserInfo;

use Lucinda\OAuth2\Server\Information;
use Lucinda\UnitTest\Validator\Arrays;
use Test\Lucinda\OAuth2\Support\Fixtures;
use Test\Lucinda\OAuth2\Support\JsonResponseWrapper;

class RequesterTest
{
    public function request()
    {
        $requester = new \Lucinda\OAuth2\Vendor\GitHub\UserInfo\Requester();
        $serverInformation = new Information([
            "authorization_url" => "https://example.com/authorize",
            "access_token_url" => "https://example.com/token",
            "scopes" => ["read:user", "user:email"],
            "resource_url" => [
                Fixtures::url("user-info/github-user.json"),
                Fixtures::url("user-info/github-emails.json")
            ]
        ]);

        $response = $requester->request($serverInformation, new JsonResponseWrapper(), "access-token");
        return [
            (new Arrays($response))->assertSize(2),
            (new Arrays($response[0]))->assertEquals(["id" => 7, "name" => "Jane GitHub"]),
            (new Arrays($response[1]))->assertEquals(["email" => "jane@github.example"])
        ];
    }
}
