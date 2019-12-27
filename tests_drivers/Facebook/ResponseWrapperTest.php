<?php
namespace Test\Lucinda\OAuth2\Vendor\Facebook;
    
use Lucinda\OAuth2\Vendor\Facebook\ResponseWrapper;
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
