<?php

namespace Test\Lucinda\OAuth2\AuthorizationCode;

use Lucinda\OAuth2\AuthorizationCode\Response;
use Lucinda\UnitTest\Result;

class ResponseTest
{
    private $response;

    public function __construct()
    {
        $this->response = new Response([
            "code"=>"test1",
            "state"=>"test2"
        ]);
    }

    public function getCode()
    {
        return new Result($this->response->getCode()=="test1");
    }


    public function getState()
    {
        return new Result($this->response->getState()=="test2");
    }
}
