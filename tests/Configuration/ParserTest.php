<?php
namespace Test\Lucinda\OAuth2\Configuration;

use Lucinda\OAuth2\Configuration\Parser;
use Lucinda\OAuth2\Configuration\TagInfo;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Objects;
use Test\Lucinda\OAuth2\Support\Fixtures;

class ParserTest
{
    public function getDrivers()
    {
        $drivers = (new Parser(Fixtures::xml()))->getDrivers();
        return [
            (new Arrays($drivers))->assertSize(5),
            (new Objects($drivers[0]))->assertInstanceOf(TagInfo::class)
        ];
    }
}
