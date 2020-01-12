<?php
namespace Test\Lucinda\OAuth2\RefreshToken;

use Lucinda\OAuth2\RefreshToken\Request;
use Lucinda\UnitTest\Result;
use Lucinda\OAuth2\WrappedExecutor;
use Lucinda\OAuth2\HttpMethod;

class RequestTest
{
    private $request;
    
    public function __construct()
    {
        $this->request = new Request("https://graph.facebook.com/v2.8/oauth/access_token");
    }
    
    public function setRefreshToken()
    {
        $this->request->setRefreshToken("asd");
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
            return new Result($e->getErrorDescription()=="Unsupported grant_type: 'refresh_token'. Supported types: authorization_code, client_credentials");
        }
    }
}
