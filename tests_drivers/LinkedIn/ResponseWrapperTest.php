<?php
namespace Test\Lucinda\OAuth2\Vendor\LinkedIn;

use Lucinda\OAuth2\Server\Exception as ServerException;
use Lucinda\OAuth2\Vendor\LinkedIn\ResponseWrapper;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;

class ResponseWrapperTest
{
    public function wrap()
    {
        $wrapper = new ResponseWrapper();
        $wrapper->wrap('{"sub":"123","name":"Jane"}');
        $results = [
            (new Arrays($wrapper->getResponse()))->assertEquals(["sub" => "123", "name" => "Jane"])
        ];

        try {
            $wrapper->wrap('{"error":"invalid_request","error_description":"Bad request"}');
            $results[] = new Result(false, "Expected LinkedIn error response to throw");
        } catch (ServerException $exception) {
            $results[] = (new Strings($exception->getMessage()))->assertEquals("Bad request");
            $results[] = (new Strings($exception->getErrorCode()))->assertEquals("invalid_request");
            $results[] = (new Strings($exception->getErrorDescription()))->assertEquals("Bad request");
        }

        return $results;
    }
}
