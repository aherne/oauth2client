<?php
namespace Test\Lucinda\OAuth2\AccessToken;

use Lucinda\UnitTest\Result;
use Lucinda\OAuth2\AccessToken\Request;
use Lucinda\OAuth2\WrappedExecutor;
use Lucinda\OAuth2\HttpMethod;

class RequestTest
{
    private $request;
    
    public function __construct()
    {
        $this->request = new Request("https://graph.facebook.com/v2.8/oauth/access_token");
    }
    
    public function setCode()
    {
        $this->request->setCode("OK");
        return new Result(true);
    }
        

    public function setClientInformation()
    {
        $this->request->setClientInformation(new \Lucinda\OAuth2\Client\Information("1769901079940433", "client_secret", "https://dev.lucinda-framework.com/login/facebook"));
        return new Result(true);
    }
        

    public function setRedirectURL()
    {
        $this->request->setRedirectURL("https://dev.lucinda-framework.com/login/facebook");
        return new Result(true);
    }
        

    public function execute()
    {
        try {
            $executor = new WrappedExecutor(new \Lucinda\OAuth2\Vendor\Facebook\ResponseWrapper());
            $executor->setHttpMethod(HttpMethod::GET);
            $this->request->execute($executor);
            return new Result(false);
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getErrorDescription()=="Invalid verification code format.");
        }
    }
}
