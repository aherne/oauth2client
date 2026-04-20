<?php
namespace Test\Lucinda\OAuth2\Vendor\LinkedIn\UserInfo;

use Lucinda\OAuth2\UserInfo;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;

class ExtractorTest
{
    public function convert()
    {
        $userInfo = (new \Lucinda\OAuth2\Vendor\LinkedIn\UserInfo\Extractor())->convert([
            "sub" => "linkedin-1",
            "name" => "Jane LinkedIn",
            "email" => "jane@linkedin.example"
        ]);

        return [
            (new Objects($userInfo))->assertInstanceOf(UserInfo::class),
            (new Strings((string) $userInfo->getId()))->assertEquals("linkedin-1"),
            (new Strings($userInfo->getName()))->assertEquals("Jane LinkedIn"),
            (new Strings($userInfo->getEmail()))->assertEquals("jane@linkedin.example")
        ];
    }
}
