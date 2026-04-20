<?php
namespace Test\Lucinda\OAuth2;

use Lucinda\OAuth2\RedirectionExecutor;
use Lucinda\UnitTest\Validator\Strings;

class RedirectionExecutorTest
{
    public function execute()
    {
        $executor = new RedirectionExecutor();
        $executor->execute("https://example.com/authorize", ["client_id" => "abc", "scope" => "openid"]);
        return (new Strings($executor->getRedirectURL()))->assertContains("client_id=abc");
    }

    public function getRedirectURL()
    {
        $executor = new RedirectionExecutor();
        $executor->execute("https://example.com/authorize", ["client_id" => "abc"]);
        return (new Strings($executor->getRedirectURL()))->assertEquals("https://example.com/authorize?client_id=abc");
    }
}
