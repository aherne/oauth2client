<?php

namespace Test\Lucinda\OAuth2;

use Lucinda\OAuth2\Wrapper;
use Lucinda\UnitTest\Result;
use Lucinda\OAuth2\Vendor\Facebook\Driver;

class WrapperTest
{
    public function getDriver()
    {
        $wrapper = new Wrapper(simplexml_load_file("unit-tests.xml"), "local");
        $driver = $wrapper->getDriver("login/facebook");
        return new Result($driver instanceof Driver);
    }
}
