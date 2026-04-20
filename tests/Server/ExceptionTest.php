<?php
namespace Test\Lucinda\OAuth2\Server;

use Lucinda\UnitTest\Validator\Strings;

class ExceptionTest
{
    public function setErrorCode()
    {
        $exception = new \Lucinda\OAuth2\Server\Exception();
        $exception->setErrorCode("invalid_grant");
        return (new Strings($exception->getErrorCode()))->assertEquals("invalid_grant");
    }

    public function getErrorCode()
    {
        $exception = new \Lucinda\OAuth2\Server\Exception();
        $exception->setErrorCode("invalid_grant");
        return (new Strings($exception->getErrorCode()))->assertEquals("invalid_grant");
    }

    public function setErrorDescription()
    {
        $exception = new \Lucinda\OAuth2\Server\Exception();
        $exception->setErrorDescription("Description");
        return (new Strings($exception->getErrorDescription()))->assertEquals("Description");
    }

    public function getErrorDescription()
    {
        $exception = new \Lucinda\OAuth2\Server\Exception();
        $exception->setErrorDescription("Description");
        return (new Strings($exception->getErrorDescription()))->assertEquals("Description");
    }

    public function setErrorURL()
    {
        $exception = new \Lucinda\OAuth2\Server\Exception();
        $exception->setErrorURL("https://example.com/error");
        return (new Strings($exception->getErrorURL()))->assertEquals("https://example.com/error");
    }

    public function getErrorURL()
    {
        $exception = new \Lucinda\OAuth2\Server\Exception();
        $exception->setErrorURL("https://example.com/error");
        return (new Strings($exception->getErrorURL()))->assertEquals("https://example.com/error");
    }

    public function setState()
    {
        $exception = new \Lucinda\OAuth2\Server\Exception();
        $exception->setState("csrf-state");
        return (new Strings($exception->getState()))->assertEquals("csrf-state");
    }

    public function getState()
    {
        $exception = new \Lucinda\OAuth2\Server\Exception();
        $exception->setState("csrf-state");
        return (new Strings($exception->getState()))->assertEquals("csrf-state");
    }
}
