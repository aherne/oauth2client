<?php
namespace Test\Lucinda\OAuth2\Vendor\Facebook;

use Lucinda\OAuth2\Server\Exception as ServerException;
use Lucinda\OAuth2\Vendor\Facebook\ResponseWrapper;
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
            $wrapper->wrap('{"error":{"code":"190","message":"Invalid OAuth access token."}}');
            $results[] = new Result(false, "Expected Facebook error response to throw");
        } catch (ServerException $exception) {
            $results[] = (new Strings($exception->getMessage()))->assertEquals("Invalid OAuth access token.");
            $results[] = (new Strings($exception->getErrorCode()))->assertEquals("190");
            $results[] = (new Strings($exception->getErrorDescription()))->assertEquals("Invalid OAuth access token.");
        }

        return $results;
    }
}
