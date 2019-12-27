<?php
namespace Test\Lucinda\OAuth2\Vendor\GitHub;
    
use Lucinda\OAuth2\Vendor\GitHub\Driver;
use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Result;

class DriverTest
{
    private $driver;
    
    public function __construct()
    {
        $this->driver = new Driver(new Information("e20344cdaa2f62ad844c", "secret", "https://dev.lucinda-framework.com/login/github"));
    }

    public function setApplicationName()
    {
        $this->driver->setApplicationName("asd");
        return new Result(true);
    }
        

    public function getResource()
    {
        try {
            $this->driver->getResource("asd", "https://api.github.com/user");
            return new Result(false);
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=="Bad credentials");
        }
    }
        

}
