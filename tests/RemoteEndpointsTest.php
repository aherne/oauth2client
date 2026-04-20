<?php
namespace Test\Lucinda\OAuth2;

use Lucinda\OAuth2\RemoteEndpoints;
use Lucinda\OAuth2\Server\Information;
use Lucinda\UnitTest\Validator\Booleans;
use Lucinda\UnitTest\Validator\Objects;

class RemoteEndpointsTest
{
    public function has()
    {
        $endpoints = new RemoteEndpoints();
        return [
            (new Booleans($endpoints->has("GitHub")))->assertTrue(),
            (new Booleans($endpoints->has("Yahoo")))->assertFalse()
        ];
    }

    public function get()
    {
        $endpoints = new RemoteEndpoints();
        return (new Objects($endpoints->get("GitHub")))->assertInstanceOf(Information::class);
    }
}
