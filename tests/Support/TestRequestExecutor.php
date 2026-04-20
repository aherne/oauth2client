<?php

namespace Test\Lucinda\OAuth2\Support;

class TestRequestExecutor implements \Lucinda\OAuth2\RequestExecutor
{
    private string $url = "";
    private array $parameters = [];

    public function execute(string $url, array $parameters): void
    {
        $this->url = $url;
        $this->parameters = $parameters;
    }

    public function getURL(): string
    {
        return $this->url;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
