<?php

namespace Test\Lucinda\OAuth2\AccessToken;

use Lucinda;
use Lucinda\UnitTest\Result;

class ResponseTest
{
    private $response;

    public function __construct()
    {
        $this->response = new Lucinda\OAuth2\AccessToken\Response(
            [
            "access_token"=>"test1",
            "token_type"=>"test2",
            "expires_in"=>123,
            "refresh_token"=>"test3",
            "scope"=>"test4",
            ]
        );
    }

    public function getAccessToken()
    {
        return new Result($this->response->getAccessToken()=="test1");
    }


    public function getTokenType()
    {
        return new Result($this->response->getTokenType()=="test2");
    }


    public function getExpiresIn()
    {
        return new Result($this->response->getExpiresIn()==time()+123);
    }


    public function getRefreshToken()
    {
        return new Result($this->response->getRefreshToken()=="test3");
    }


    public function getScope()
    {
        return new Result($this->response->getScope()=="test4");
    }
}
