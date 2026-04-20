<?php
namespace Test\Lucinda\OAuth2\Configuration;

use Lucinda\OAuth2\Configuration\TagInfo;
use Lucinda\UnitTest\Validator\Booleans;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\OAuth2\Support\Fixtures;

class TagInfoTest
{
    public function getDriverName()
    {
        $info = new TagInfo(Fixtures::xml()->oauth2->driver[0]);
        return (new Strings($info->getDriverName()))->assertEquals("Facebook");
    }

    public function getClientId()
    {
        $info = new TagInfo(Fixtures::xml()->oauth2->driver[0]);
        return (new Strings($info->getClientId()))->assertEquals("FACEBOOK_CLIENT_ID");
    }

    public function getClientSecret()
    {
        $info = new TagInfo(Fixtures::xml()->oauth2->driver[0]);
        return (new Strings($info->getClientSecret()))->assertEquals("FACEBOOK_CLIENT_SECRET");
    }

    public function getCallbackUrl()
    {
        $info = new TagInfo(Fixtures::xml()->oauth2->driver[0]);
        return (new Strings($info->getCallbackUrl()))->assertEquals("login/facebook");
    }

    public function getApplicationName()
    {
        $xml = Fixtures::xml();
        $facebook = new TagInfo($xml->oauth2->driver[0]);
        $github = new TagInfo($xml->oauth2->driver[2]);
        return [
            (new Booleans($facebook->getApplicationName() === null))->assertTrue(),
            (new Strings($github->getApplicationName()))->assertEquals("OAuth2 Test App")
        ];
    }
}
