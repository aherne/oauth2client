<?php
namespace Test\Lucinda\OAuth2\Server;

use Lucinda\OAuth2\Server\Information;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;

class InformationTest
{
    public function getAuthorizationUrl()
    {
        $information = new Information($this->getInfo());
        return (new Strings($information->getAuthorizationUrl()))->assertEquals("https://example.com/authorize");
    }

    public function getAccessTokenUrl()
    {
        $information = new Information($this->getInfo());
        return (new Strings($information->getAccessTokenUrl()))->assertEquals("https://example.com/token");
    }

    public function getScopes()
    {
        $information = new Information($this->getInfo());
        return (new Arrays($information->getScopes()))->assertEquals(["openid", "email"]);
    }

    public function getResourceURL()
    {
        $information = new Information($this->getInfo());
        return (new Strings($information->getResourceURL()))->assertEquals("https://example.com/userinfo");
    }

    public function getResourceFields()
    {
        $information = new Information($this->getInfo());
        return (new Arrays($information->getResourceFields()))->assertEquals(["id", "name", "email"]);
    }

    private function getInfo(): array
    {
        return [
            "authorization_url" => "https://example.com/authorize",
            "access_token_url" => "https://example.com/token",
            "scopes" => ["openid", "email"],
            "resource_url" => "https://example.com/userinfo",
            "resource_fields" => ["id", "name", "email"]
        ];
    }
}
