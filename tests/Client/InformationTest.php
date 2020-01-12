<?php
namespace Test\Lucinda\OAuth2\Client;

use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Result;

class InformationTest
{
    private $information;
    
    public function __construct()
    {
        $this->information = new Information("test1", "test2", "http://www.example.com");
    }
    
    public function getApplicationID()
    {
        return new Result($this->information->getApplicationID()=="test1");
    }
        

    public function getApplicationSecret()
    {
        return new Result($this->information->getApplicationSecret()=="test2");
    }
        

    public function getSiteURL()
    {
        return new Result($this->information->getSiteURL()=="http://www.example.com");
    }
}
