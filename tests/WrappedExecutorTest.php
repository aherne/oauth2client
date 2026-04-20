<?php
namespace Test\Lucinda\OAuth2;

use Lucinda\OAuth2\WrappedExecutor;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;
use Lucinda\UnitTest\Validator\Strings;
use Lucinda\URL\Request\Method;
use Test\Lucinda\OAuth2\Support\Fixtures;
use Test\Lucinda\OAuth2\Support\JsonResponseWrapper;
use Test\Lucinda\OAuth2\Support\Reflection;

class WrappedExecutorTest
{
    public function setHttpMethod()
    {
        $executor = new WrappedExecutor(new JsonResponseWrapper());
        $executor->setHttpMethod(Method::GET);
        return (new Strings(Reflection::get($executor, "httpMethod")->value))->assertEquals("GET");
    }

    public function setUserAgent()
    {
        $executor = new WrappedExecutor(new JsonResponseWrapper());
        $executor->setUserAgent("OAuth2 Test Agent");
        return (new Strings(Reflection::get($executor, "userAgent")))->assertEquals("OAuth2 Test Agent");
    }

    public function addHeader()
    {
        $executor = new WrappedExecutor(new JsonResponseWrapper());
        $executor->addHeader("X-Test", "value");
        return (new Arrays(Reflection::get($executor, "headers")))->assertEquals(["X-Test" => "value"]);
    }

    public function execute()
    {
        $responseWrapper = new JsonResponseWrapper();
        $executor = new WrappedExecutor($responseWrapper);
        $executor->setHttpMethod(Method::GET);
        $executor->execute(Fixtures::url("wrapped-executor.json"), []);

        return [
            (new Objects(Reflection::get($executor, "responseWrapper")))->assertInstanceOf(JsonResponseWrapper::class),
            (new Arrays($responseWrapper->getResponse()))->assertEquals(["status" => "ok", "user" => "tester"])
        ];
    }
}
