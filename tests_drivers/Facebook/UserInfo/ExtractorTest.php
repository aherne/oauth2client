<?php
namespace Test\Lucinda\OAuth2\Vendor\Facebook\UserInfo;

use Lucinda\OAuth2\UserInfo;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;

class ExtractorTest
{
    public function convert()
    {
        $userInfo = (new \Lucinda\OAuth2\Vendor\Facebook\UserInfo\Extractor())->convert([
            "id" => "facebook-1",
            "name" => "Jane Facebook",
            "email" => "jane@facebook.example"
        ]);

        return [
            (new Objects($userInfo))->assertInstanceOf(UserInfo::class),
            (new Strings((string) $userInfo->getId()))->assertEquals("facebook-1"),
            (new Strings($userInfo->getName()))->assertEquals("Jane Facebook"),
            (new Strings($userInfo->getEmail()))->assertEquals("jane@facebook.example")
        ];
    }
}
