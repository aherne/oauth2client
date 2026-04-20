<?php
namespace Test\Lucinda\OAuth2\Vendor\GitHub;

use Lucinda\OAuth2\Client\Information;
use Lucinda\OAuth2\Server\Information as ServerInformation;
use Lucinda\OAuth2\Vendor\GitHub\Driver;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\OAuth2\Support\Reflection;

class DriverTest
{
    public function setApplicationName()
    {
        $driver = new Driver(
            new Information("client-id", "client-secret", "https://app.example/callback"),
            new ServerInformation([
                "authorization_url" => "https://github.com/login/oauth/authorize",
                "access_token_url" => "https://github.com/login/oauth/access_token",
                "scopes" => ["read:user", "user:email"],
                "resource_url" => ["https://api.github.com/user", "https://api.github.com/user/emails"]
            ])
        );
        $driver->setApplicationName("OAuth2 Test App");
        return (new Strings(Reflection::get($driver, "applicationName")))->assertEquals("OAuth2 Test App");
    }
}
