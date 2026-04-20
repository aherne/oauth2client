<?php

namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Client\Exception as ClientException;
use Lucinda\OAuth2\Server\Information as ServerInformation;

class RemoteEndpoints
{
    private array $endpoints;

    public function __construct()
    {
        $json = json_decode(file_get_contents(dirname(__DIR__)."/endpoints.json"), true);
        foreach ($json as $vendor=>$info) {
            $this->endpoints[$vendor] = new ServerInformation($info);
        }
    }

    public function has(string $string): bool
    {
        return isset($this->endpoints[$string]);
    }

    public function get(string $vendor): ServerInformation
    {
        return $this->endpoints[$vendor];
    }
}
