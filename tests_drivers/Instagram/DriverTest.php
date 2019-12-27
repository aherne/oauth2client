<?php
namespace Test\Lucinda\OAuth2\Vendor\Instagram;
    
use Lucinda\OAuth2\Vendor\Instagram\Driver;
use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Result;

class DriverTest
{
    private $driver;
    
    public function __construct()
    {
        $this->driver = new Driver(new Information("32eea8d114e842899e57222b9779c6b5", "secret", "https://dev.lucinda-framework.com/login/instagram"));
    }
    
    public function getAccessToken()
    {
        try {
            $this->driver->getAccessToken("asd");
            return new Result(false);
        } catch(\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=="Invalid Client Secret");
        }
    }
        

    public function getResource()
    {
        try {
            $this->driver->getResource("asd", "https://api.instagram.com/v1/users/self/");
            return new Result(false);
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=="The access_token provided is invalid.");
        }
    }
        

}
