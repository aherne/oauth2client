<?php
namespace Test\Lucinda\OAuth2;
    
use Lucinda\OAuth2\Vendor\Facebook\Driver;
use Lucinda\UnitTest\Result;

class DriverTest
{
    private $driver;
    
    public function __construct()
    {
        $this->driver = new Driver(new \Lucinda\OAuth2\Client\Information("1769901079940433", "app_secret", "https://dev.lucinda-framework.com/login/facebook"));
    }

    public function getAuthorizationCodeEndpoint()
    {
        $endpoint = $this->driver->getAuthorizationCodeEndpoint(["public_profile", "email"]);
        return new Result($endpoint=='https://www.facebook.com/v2.8/dialog/oauth?response_type=code&client_id=1769901079940433&redirect_uri=https%3A%2F%2Fdev.lucinda-framework.com%2Flogin%2Ffacebook&scope=public_profile+email');
    }
        

    public function getAccessToken()
    {
        try {
            $this->driver->getAccessToken("asd");
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=="Invalid verification code format.");
        }
    }
        

    public function refreshAccessToken()
    {
        try {
            $this->driver->refreshAccessToken("asd");
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=="Unsupported grant_type: 'refresh_token'. Supported types: authorization_code, client_credentials");
        }
    }
        

    public function getResource()
    {
        try {
            $this->driver->getResource("asd", "https://graph.facebook.com/v2.8/me");
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=="Invalid OAuth access token.");
        }
    }
        

}
