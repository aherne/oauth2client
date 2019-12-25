<?php
namespace Test\Lucinda\OAuth2;
    
use Lucinda\OAuth2\WrappedExecutor;
use Lucinda\OAuth2\Vendor\Facebook\ResponseWrapper;
use Lucinda\OAuth2\HttpMethod;
use Lucinda\UnitTest\Result;
use Lucinda\OAuth2\Server\Exception;

class WrappedExecutorTest
{
    private $wrapper;
    private $executor;
    
    public function __construct()
    {
        $this->executor = new WrappedExecutor(new ResponseWrapper());
    }

    public function setHttpMethod()
    {
        $this->executor->setHttpMethod(HttpMethod::GET);
        return new Result(true);
    }
        

    public function setUserAgent()
    {
        $this->executor->setUserAgent("asd");
        return new Result(true);
    }
        

    public function addHeader()
    {
        $this->executor->addHeader("fgh", "jkl");
        return new Result(true);
    }
        

    public function execute()
    {
        try {
            $this->executor->execute("https://graph.facebook.com/v2.8/me", []);
        } catch (Exception $e) {
            return new Result($e->getErrorDescription()=="An active access token must be used to query information about the current user.");
        }
    }
        

}
