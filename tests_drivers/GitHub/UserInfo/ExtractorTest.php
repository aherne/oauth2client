<?php
namespace Test\Lucinda\OAuth2\Vendor\GitHub\UserInfo;

use Lucinda\OAuth2\UserInfo;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;

class ExtractorTest
{
    public function convert()
    {
        $userInfo = (new \Lucinda\OAuth2\Vendor\GitHub\UserInfo\Extractor())->convert([
            ["id" => 7, "name" => "Jane GitHub"],
            ["email" => "jane@github.example"]
        ]);

        return [
            (new Objects($userInfo))->assertInstanceOf(UserInfo::class),
            (new Strings((string) $userInfo->getId()))->assertEquals("7"),
            (new Strings($userInfo->getName()))->assertEquals("Jane GitHub"),
            (new Strings($userInfo->getEmail()))->assertEquals("jane@github.example")
        ];
    }
}
