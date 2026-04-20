<?php
namespace Test\Lucinda\OAuth2\Vendor\Microsoft\UserInfo;

use Lucinda\OAuth2\UserInfo;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;

class ExtractorTest
{
    public function convert()
    {
        $userInfo = (new \Lucinda\OAuth2\Vendor\Microsoft\UserInfo\Extractor())->convert([
            "id" => "microsoft-1",
            "displayName" => "Jane Microsoft",
            "mail" => "jane@microsoft.example",
            "userPrincipalName" => "fallback@microsoft.example"
        ]);

        return [
            (new Objects($userInfo))->assertInstanceOf(UserInfo::class),
            (new Strings((string) $userInfo->getId()))->assertEquals("microsoft-1"),
            (new Strings($userInfo->getName()))->assertEquals("Jane Microsoft"),
            (new Strings($userInfo->getEmail()))->assertEquals("jane@microsoft.example")
        ];
    }
}
