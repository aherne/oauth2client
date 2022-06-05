<?php

namespace Test\Lucinda\OAuth2\Vendor\Instagram;

use Lucinda\OAuth2\Vendor\Instagram\ResponseWrapper;
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
