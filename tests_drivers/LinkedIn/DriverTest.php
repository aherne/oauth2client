<?php

namespace Test\Lucinda\OAuth2\Vendor\LinkedIn;

use Lucinda\OAuth2\Vendor\LinkedIn\Driver;
use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Result;

class DriverTest
{
    public function getResource()
    {
        try {
            $driver = new Driver(new Information("86360yoaicu3my", "secret", "https://dev.lucinda-framework.com/login/linkedin"));
            $driver->getResource("asd", "https://api.linkedin.com/v2/me");
            return new Result(false);
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=="Invalid access token");
        }
    }
}
