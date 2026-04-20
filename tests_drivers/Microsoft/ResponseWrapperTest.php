<?php
namespace Test\Lucinda\OAuth2\Vendor\Microsoft;

use Lucinda\OAuth2\Server\Exception as ServerException;
use Lucinda\OAuth2\Vendor\Microsoft\ResponseWrapper;
use Lucinda\UnitTest\Result;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;

class ResponseWrapperTest
{
    public function wrap()
    {
        $wrapper = new ResponseWrapper();
        $wrapper->wrap('{"id":"1","displayName":"Jane"}');
        $results = [
            (new Arrays($wrapper->getResponse()))->assertEquals(["id" => "1", "displayName" => "Jane"])
        ];

        try {
            $wrapper->wrap('{"error":"invalid_grant","error_codes":[70000,70001],"error_description":"Grant expired"}');
            $results[] = new Result(false, "Expected Microsoft error response to throw");
        } catch (ServerException $exception) {
            $results[] = (new Strings($exception->getMessage()))->assertEquals("invalid_grant");
            $results[] = (new Strings($exception->getErrorCode()))->assertEquals("70000,70001");
            $results[] = (new Strings($exception->getErrorDescription()))->assertEquals("Grant expired");
        }

        return $results;
    }
}
