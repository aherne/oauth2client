<?php
namespace Test\Lucinda\OAuth2\Vendor\LinkedIn;
    
use Lucinda\OAuth2\Vendor\LinkedIn\ResponseWrapper;
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
