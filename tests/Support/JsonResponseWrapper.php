<?php

namespace Test\Lucinda\OAuth2\Support;

class JsonResponseWrapper extends \Lucinda\OAuth2\ResponseWrapper
{
    public function wrap(string $response): void
    {
        $this->response = json_decode($response, true);
    }
}
