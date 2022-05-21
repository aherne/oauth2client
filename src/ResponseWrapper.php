<?php

namespace Lucinda\OAuth2;

/**
 * Defines response wrapping methods.
 */
abstract class ResponseWrapper
{
    /**
     * @var array<mixed>
     */
    protected array $response;

    /**
     * Parses response received from OAuth2 server.
     *
     * @param string $response
     */
    abstract public function wrap(string $response): void;

    /**
     * Gets parsed response
     *
     * @return array<mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}
