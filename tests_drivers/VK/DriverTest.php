<?php
namespace Test\Lucinda\OAuth2\Vendor\VK;
    
use Lucinda\OAuth2\Vendor\VK\Driver;
use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Result;

class DriverTest
{

    public function getResource()
    {
        try {
            $driver = new Driver(new Information("6309404", "secret", "https://dev.lucinda-framework.com/login/vk"));
            $driver->getResource("asd", "https://api.vk.com/method/users.get");
            return new Result(false);
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=="User authorization failed: invalid access_token (4).");
        }
    }
        

}
