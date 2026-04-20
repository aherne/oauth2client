<?php
namespace Test\Lucinda\OAuth2\Vendor\GitHub;

use Lucinda\OAuth2\Server\Exception as ServerException;
use Lucinda\OAuth2\Vendor\GitHub\ResponseWrapper;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;

class ResponseWrapperTest
{
    public function wrap()
    {
        $wrapper = new ResponseWrapper();
        $wrapper->wrap('{"id":7,"name":"Jane"}');
        $results = [
            (new Arrays($wrapper->getResponse()))->assertEquals(["id" => 7, "name" => "Jane"])
        ];

        try {
            $wrapper->wrap('{"message":"Bad credentials"}');
            $results[] = new Result(false, "Expected GitHub message response to throw");
        } catch (ServerException $exception) {
            $results[] = (new Strings($exception->getMessage()))->assertEquals("Bad credentials");
        }

        return $results;
    }
}
