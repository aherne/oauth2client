<?php
namespace Test\Lucinda\OAuth2\Configuration;

use Lucinda\OAuth2\Configuration\Parser;
use Lucinda\UnitTest\Result;

class ParserTest
{
    public function getDrivers()
    {
        $parser = new Parser(simplexml_load_file("unit-tests.xml"), "local");
        return new Result(sizeof($parser->getDrivers())==8);
    }
}
