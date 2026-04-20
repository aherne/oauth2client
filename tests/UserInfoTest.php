<?php
namespace Test\Lucinda\OAuth2;

use Lucinda\OAuth2\UserInfo;
use Lucinda\UnitTest\Validator\Strings;

class UserInfoTest
{
    public function getId()
    {
        $userInfo = new UserInfo("user-1", "Jane Doe", "jane@example.com");
        return (new Strings((string) $userInfo->getId()))->assertEquals("user-1");
    }

    public function getName()
    {
        $userInfo = new UserInfo("user-1", "Jane Doe", "jane@example.com");
        return (new Strings($userInfo->getName()))->assertEquals("Jane Doe");
    }

    public function getEmail()
    {
        $userInfo = new UserInfo("user-1", "Jane Doe", "jane@example.com");
        return (new Strings($userInfo->getEmail()))->assertEquals("jane@example.com");
    }
}
