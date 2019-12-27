<?php
namespace Test\Lucinda\OAuth2\Configuration;
    
use Lucinda\OAuth2\Configuration\TagInfo;
use Lucinda\UnitTest\Result;

class TagInfoTest
{
    private $info;
    
    public function __construct()
    {
        $xml = simplexml_load_file("unit-tests.xml");
        $this->info = new TagInfo($xml->oauth2->local->driver);
    }
    
    public function getDriverName()
    {
        return new Result($this->info->getDriverName()=="Facebook");
    }
        

    public function getClientId()
    {
        return new Result($this->info->getClientId()=="YOUR_CLIENT_ID");
    }
        

    public function getClientSecret()
    {
        return new Result($this->info->getClientSecret()=="YOUR_CLIENT_SECRET");
    }
        

    public function getCallbackUrl()
    {
        return new Result($this->info->getCallbackUrl()=="login/facebook");
    }
        

    public function getApplicationName()
    {
        return new Result($this->info->getApplicationName()=="");
    }
        

    public function getScopes()
    {
        return new Result($this->info->getScopes()==["public_profile","email"]);
    }
        

}
