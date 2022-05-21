<?php

namespace Test\Lucinda\OAuth2;

use Lucinda\OAuth2\Vendor\Facebook\ResponseWrapper;
use Lucinda\UnitTest\Result;

class ResponseWrapperTest
{
    private $wrapper;

    public function __construct()
    {
        $this->wrapper = new ResponseWrapper();
    }

    public function wrap()
    {
        $this->wrapper->wrap(json_encode(["asd"=>"fgh"]));
        return new Result(true);
    }


    public function getResponse()
    {
        return new Result($this->wrapper->getResponse()==["asd"=>"fgh"]);
    }
}
