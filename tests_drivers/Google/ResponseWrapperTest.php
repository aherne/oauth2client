<?php
namespace Test\Lucinda\OAuth2\Vendor\Google;

use Lucinda\OAuth2\Server\Exception as ServerException;
use Lucinda\OAuth2\Vendor\Google\ResponseWrapper;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;

class ResponseWrapperTest
{
    public function wrap()
    {
        $wrapper = new ResponseWrapper();
        $wrapper->wrap('{"id":"1","name":"Jane"}');
        $results = [
            (new Arrays($wrapper->getResponse()))->assertEquals(["id" => "1", "name" => "Jane"])
        ];

        try {
            $wrapper->wrap('{"error":{"code":"401","message":"Invalid Credentials"}}');
            $results[] = new Result(false, "Expected Google error response to throw");
        } catch (ServerException $exception) {
            $results[] = (new Strings($exception->getMessage()))->assertEquals("Invalid Credentials");
            $results[] = (new Strings($exception->getErrorCode()))->assertEquals("401");
            $results[] = (new Strings($exception->getErrorDescription()))->assertEquals("Invalid Credentials");
        }

        return $results;
    }
}
