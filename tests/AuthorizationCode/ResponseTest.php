<?php
namespace Test\Lucinda\OAuth2\AuthorizationCode;

use Lucinda\OAuth2\AuthorizationCode\Response;
use Lucinda\UnitTest\Validator\Strings;

class ResponseTest
{
    public function getCode()
    {
        $response = new Response(["code" => "auth-code", "state" => "csrf-state"]);
        return (new Strings($response->getCode()))->assertEquals("auth-code");
    }

    public function getState()
    {
        $response = new Response(["code" => "auth-code", "state" => "csrf-state"]);
        return (new Strings($response->getState()))->assertEquals("csrf-state");
    }
}
