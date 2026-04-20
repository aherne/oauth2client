<?php
namespace Test\Lucinda\OAuth2\DriverRequest;

use Lucinda\OAuth2\DriverRequest\AuthorizationCodeRequestWrapper;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\OAuth2\Support\Reflection;

class AuthorizationCodeRequestWrapperTest
{
    public function getResponse()
    {
        $wrapper = Reflection::newWithoutConstructor(AuthorizationCodeRequestWrapper::class);
        Reflection::set($wrapper, "redirectURL", "https://example.com/authorize?code=123");
        return (new Strings($wrapper->getResponse()))->assertEquals("https://example.com/authorize?code=123");
    }
}
