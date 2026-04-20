<?php
namespace Test\Lucinda\OAuth2;

use Lucinda\OAuth2\Wrapper;
use Lucinda\OAuth2\Vendor\Facebook\Driver as FacebookDriver;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;
use Test\Lucinda\OAuth2\Support\Fixtures;

class WrapperTest
{
    public function getDriver()
    {
        $wrapper = new Wrapper(Fixtures::xml());
        return [
            (new Arrays($wrapper->getDriver()))->assertSize(5),
            (new Objects($wrapper->getDriver("login/facebook")))->assertInstanceOf(FacebookDriver::class)
        ];
    }
}
