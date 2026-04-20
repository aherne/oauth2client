<?php
namespace Test\Lucinda\OAuth2\Client;

use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Validator\Strings;

class InformationTest
{
    public function getApplicationID()
    {
        $information = new Information("client-id", "client-secret", "https://app.example/callback");
        return (new Strings($information->getApplicationID()))->assertEquals("client-id");
    }

    public function getApplicationSecret()
    {
        $information = new Information("client-id", "client-secret", "https://app.example/callback");
        return (new Strings($information->getApplicationSecret()))->assertEquals("client-secret");
    }

    public function getSiteURL()
    {
        $information = new Information("client-id", "client-secret", "https://app.example/callback");
        return (new Strings($information->getSiteURL()))->assertEquals("https://app.example/callback");
    }
}
