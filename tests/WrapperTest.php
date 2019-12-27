<?php
namespace Test\Lucinda\OAuth2;
    
use Lucinda\OAuth2\Wrapper;
use Lucinda\UnitTest\Result;
use Lucinda\OAuth2\Vendor\Facebook\Driver;

class WrapperTest
{

    public function getDrivers()
    {
        $wrapper = new Wrapper(simplexml_load_file("unit-tests.xml"), "local");
        $drivers = $wrapper->getDrivers();
        $result = [];
        $result[] = new Result(sizeof($drivers)==8);
        $result[] = new Result($drivers[0] instanceof Driver);
        return $result;
    }
        

}
