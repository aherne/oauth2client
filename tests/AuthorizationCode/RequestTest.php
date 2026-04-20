<?php
namespace Test\Lucinda\OAuth2\AuthorizationCode;

use Lucinda\OAuth2\AuthorizationCode\Request;
use Lucinda\OAuth2\Client\Information;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\OAuth2\Support\Reflection;
use Test\Lucinda\OAuth2\Support\TestRequestExecutor;

class RequestTest
{
    public function setClientInformation()
    {
        $request = new Request("https://example.com/authorize");
        $request->setClientInformation(new Information("client-id", "client-secret", "https://app.example/callback"));
        return (new Strings(Reflection::get($request, "clientInformation")->getApplicationID()))->assertEquals("client-id");
    }

    public function setRedirectURL()
    {
        $request = new Request("https://example.com/authorize");
        $request->setRedirectURL("https://app.example/redirect");
        return (new Strings(Reflection::get($request, "redirectURL")))->assertEquals("https://app.example/redirect");
    }

    public function setScope()
    {
        $request = new Request("https://example.com/authorize");
        $request->setScope("openid profile email");
        return (new Strings(Reflection::get($request, "scope")))->assertEquals("openid profile email");
    }

    public function setState()
    {
        $request = new Request("https://example.com/authorize");
        $request->setState("csrf-token");
        return (new Strings(Reflection::get($request, "state")))->assertEquals("csrf-token");
    }

    public function execute()
    {
        $request = new Request("https://example.com/authorize");
        $request->setClientInformation(new Information("client-id", "client-secret", "https://app.example/callback"));
        $request->setRedirectURL("https://app.example/redirect");
        $request->setScope("openid profile email");
        $request->setState("csrf-token");
        $executor = new TestRequestExecutor();
        $request->execute($executor);

        return [
            (new Strings($executor->getURL()))->assertEquals("https://example.com/authorize"),
            (new Arrays($executor->getParameters()))->assertEquals([
                "response_type" => "code",
                "client_id" => "client-id",
                "redirect_uri" => "https://app.example/redirect",
                "scope" => "openid profile email",
                "state" => "csrf-token"
            ])
        ];
    }
}
