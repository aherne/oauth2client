<?php
namespace Test\Lucinda\OAuth2\Server;

use Lucinda\UnitTest\Result;

class ExceptionTest
{
    private $exception;
    
    public function __construct()
    {
        $this->exception = new \Lucinda\OAuth2\Server\Exception();
    }
    

    public function setErrorCode()
    {
        $this->exception->setErrorCode("test1");
        return new Result(true);
    }
        

    public function getErrorCode()
    {
        return new Result($this->exception->getErrorCode()=="test1");
    }
        

    public function setErrorDescription()
    {
        $this->exception->setErrorDescription("test2");
        return new Result(true);
    }
        

    public function getErrorDescription()
    {
        return new Result($this->exception->getErrorDescription()=="test2");
    }
        

    public function setErrorURL()
    {
        $this->exception->setErrorURL("http://www.example.com");
        return new Result(true);
    }
        

    public function getErrorURL()
    {
        return new Result($this->exception->getErrorURL()=="http://www.example.com");
    }
        

    public function setState()
    {
        $this->exception->setState("test3");
        return new Result(true);
    }
        

    public function getState()
    {
        return new Result($this->exception->getState()=="test3");
    }
}
