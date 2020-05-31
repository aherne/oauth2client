<?php
namespace Test\Lucinda\OAuth2\Server;

use Lucinda\OAuth2\Server\Information;
use Lucinda\UnitTest\Result;

class InformationTest
{
    private $information;
    
    public function __construct()
    {
        $this->information = new Information("https://www.facebook.com/v2.8/dialog/oauth", "https://graph.facebook.com/v2.8/oauth/access_token");
    }

    public function getAuthorizationEndpoint()
    {
        return new Result($this->information->getAuthorizationEndpoint()=="https://www.facebook.com/v2.8/dialog/oauth");
    }
        

    public function getTokenEndpoint()
    {
        return new Result($this->information->getTokenEndpoint()=="https://graph.facebook.com/v2.8/oauth/access_token");
    }
}
