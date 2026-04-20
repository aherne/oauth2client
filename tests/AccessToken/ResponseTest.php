<?php
namespace Test\Lucinda\OAuth2\AccessToken;

use Lucinda\OAuth2\AccessToken\Response;
use Lucinda\UnitTest\Validator\Integers;
use Lucinda\UnitTest\Validator\Strings;

class ResponseTest
{
    public function getAccessToken()
    {
        $response = new Response($this->getPayload());
        return (new Strings($response->getAccessToken()))->assertEquals("access-token");
    }

    public function getTokenType()
    {
        $response = new Response($this->getPayload());
        return (new Strings($response->getTokenType()))->assertEquals("Bearer");
    }

    public function getExpiresIn()
    {
        $start = time();
        $response = new Response($this->getPayload());
        return [
            (new Integers($response->getExpiresIn()))->assertGreaterEquals($start + 120),
            (new Integers($response->getExpiresIn()))->assertSmallerEquals($start + 122)
        ];
    }

    public function getRefreshToken()
    {
        $response = new Response($this->getPayload());
        return (new Strings($response->getRefreshToken()))->assertEquals("refresh-token");
    }

    public function getScope()
    {
        $response = new Response($this->getPayload());
        return (new Strings($response->getScope()))->assertEquals("openid email");
    }

    private function getPayload(): array
    {
        return [
            "access_token" => "access-token",
            "token_type" => "Bearer",
            "expires_in" => 120,
            "refresh_token" => "refresh-token",
            "scope" => "openid email"
        ];
    }
}
