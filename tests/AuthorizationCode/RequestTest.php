<?php
namespace Test\Lucinda\OAuth2\AuthorizationCode;
    
use Lucinda\OAuth2\AuthorizationCode\Request;
use Lucinda\UnitTest\Result;
use Lucinda\OAuth2\RedirectionExecutor;
use Lucinda\UnitTest\Validator\Strings;

class RequestTest
{
    private $request;
    
    public function __construct()
    {
        $this->request = new Request("https://www.facebook.com/v2.8/dialog/oauth");
    }

    public function setClientInformation()
    {
        $this->request->setClientInformation(new \Lucinda\OAuth2\Client\Information("client_id", "client_secret", ""));
        return new Result(true);
    }
        

    public function setRedirectURL()
    {
        $this->request->setRedirectURL("https://dev.lucinda-framework.com/login/facebook");
        return new Result(true);
    }
        

    public function setScope()
    {
        $this->request->setScope("test_scope");
        return new Result(true);
    }
        

    public function setState()
    {
        $this->request->setState("test_state");
        return new Result(true);
    }
        

    public function execute()
    {
        $executor = new RedirectionExecutor();
        $this->request->execute($executor);
        return new Result($executor->getRedirectURL()=="https://www.facebook.com/v2.8/dialog/oauth?response_type=code&client_id=client_id&redirect_uri=https%3A%2F%2Fdev.lucinda-framework.com%2Flogin%2Ffacebook&scope=test_scope&state=test_state");
    }
        

}
