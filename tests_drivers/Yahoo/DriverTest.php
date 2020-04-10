<?php
namespace Test\Lucinda\OAuth2\Vendor\Yahoo;

use Lucinda\OAuth2\Vendor\Yahoo\Driver;
use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Result;

class DriverTest
{
    public function getResource()
    {
        try {
            $driver = new Driver(new Information("dj0yJmk9YVA4cGxkQkFmTUg5JmQ9WVdrOVowZEZXRFV3TkdNbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PTdi", "secret", "https://dev.lucinda-framework.com/login/yahoo"));
            $driver->getResource("asd", "https://social.yahooapis.com/v1/user/me/profile");
            return new Result(false);
        } catch (\Lucinda\OAuth2\Server\Exception $e) {
            return new Result($e->getMessage()=='Not Authorized - Either YT cookies or a valid OAuth token must be passed for authorization');
        }
    }
}
