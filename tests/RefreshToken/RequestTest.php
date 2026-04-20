<?php
namespace Test\Lucinda\OAuth2\RefreshToken;

use Lucinda\OAuth2\Client\Information;
use Lucinda\OAuth2\RefreshToken\Request;
use Lucinda\UnitTest\Validator\Arrays;
use Lucinda\UnitTest\Validator\Strings;
use Test\Lucinda\OAuth2\Support\Reflection;
use Test\Lucinda\OAuth2\Support\TestRequestExecutor;

class RequestTest
{
    public function setRefreshToken()
    {
        $request = new Request("https://example.com/token");
        $request->setRefreshToken("refresh-token");
        return (new Strings(Reflection::get($request, "refreshToken")))->assertEquals("refresh-token");
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
        $request->setRefreshToken("refresh-token");
        $request->setRedirectURL("https://app.example/redirect");
        $executor = new TestRequestExecutor();
        $request->execute($executor);

        return [
            (new Strings($executor->getURL()))->assertEquals("https://example.com/token"),
            (new Arrays($executor->getParameters()))->assertEquals([
                "grant_type" => "refresh_token",
                "client_id" => "client-id",
                "refresh_token" => "refresh-token",
                "client_secret" => "client-secret",
                "redirect_uri" => "https://app.example/redirect"
            ])
        ];
    }
}
