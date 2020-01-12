<?php
namespace Test\Lucinda\OAuth2\Vendor\Yahoo;

use Lucinda\OAuth2\Vendor\Yahoo\ResponseWrapper;
use Lucinda\UnitTest\Result;

class ResponseWrapperTest
{
    public function wrap()
    {
        $wrapper = new ResponseWrapper();
        $wrapper->wrap(json_encode(["x"=>"y"]));
        return new Result($wrapper->getResponse()==["x"=>"y"]);
    }
}
