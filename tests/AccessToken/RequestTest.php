<?php
namespace Test\Lucinda\OAuth2\AccessToken;

use Lucinda\OAuth2\AccessToken\Request;
use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\OAuth2\Support\Reflection;
use Test\Lucinda\OAuth2\Support\TestRequestExecutor;

class RequestTest
{
    public function setCode()
    {
        $request = new Request("https://example.com/token");
        $request->setCode("authorization-code");
        return (new Strings(Reflection::get($request, "code")))->assertEquals("authorization-code");
    }

    public function setClientInformation()
    {
        $request = new Request("https://example.com/token");
        $request->setClientInformation(new Information("client-id", "client-secret", "https://app.example/callback"));
        return (new Strings(Reflection::get($request, "clientInformation")->getApplicationID()))->assertEquals("client-id");
    }

    public function setRedirectURL()
    {
        $request = new Request("https://example.com/token");
        $request->setRedirectURL("https://app.example/redirect");
        return (new Strings(Reflection::get($request, "redirectURL")))->assertEquals("https://app.example/redirect");
    }

    public function execute()
    {
        $request = new Request("https://example.com/token");
        $request->setClientInformation(new Information("client-id", "client-secret", "https://app.example/callback"));
        $request->setCode("authorization-code");
        $request->setRedirectURL("https://app.example/redirect");
        $executor = new TestRequestExecutor();
        $request->execute($executor);

        return [
            (new Strings($executor->getURL()))->assertEquals("https://example.com/token"),
            (new Arrays($executor->getParameters()))->assertEquals([
                "grant_type" => "authorization_code",
                "client_id" => "client-id",
                "code" => "authorization-code",
                "client_secret" => "client-secret",
                "redirect_uri" => "https://app.example/redirect"
            ])
        ];
    }
}
