<?php
namespace Test\Lucinda\OAuth2\Vendor\Google\UserInfo;

use Lucinda\OAuth2\UserInfo;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;

class ExtractorTest
{
    public function convert()
    {
        $userInfo = (new \Lucinda\OAuth2\Vendor\Google\UserInfo\Extractor())->convert([
            "id" => "google-1",
            "name" => "Jane Google",
            "email" => "jane@google.example"
        ]);

        return [
            (new Objects($userInfo))->assertInstanceOf(UserInfo::class),
            (new Strings((string) $userInfo->getId()))->assertEquals("google-1"),
            (new Strings($userInfo->getName()))->assertEquals("Jane Google"),
            (new Strings($userInfo->getEmail()))->assertEquals("jane@google.example")
        ];
    }
}
