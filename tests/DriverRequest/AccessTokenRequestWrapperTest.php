<?php
namespace Test\Lucinda\OAuth2\DriverRequest;

use Lucinda\OAuth2\AccessToken\Response;
use Lucinda\OAuth2\DriverRequest\AccessTokenRequestWrapper;
use Lucinda\UnitTest\Validator\Objects;
use Test\Lucinda\OAuth2\Support\Reflection;

class AccessTokenRequestWrapperTest
{
    public function getResponse()
    {
        $wrapper = Reflection::newWithoutConstructor(AccessTokenRequestWrapper::class);
        Reflection::set($wrapper, "accessTokenResponse", new Response(["access_token" => "abc"]));
        return (new Objects($wrapper->getResponse()))->assertInstanceOf(Response::class);
    }
}
