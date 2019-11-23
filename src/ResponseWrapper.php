<?php
namespace Lucinda\OAuth2;

/**
 * Defines response wrapping methods.
 */
abstract class ResponseWrapper
{
    protected $response;
    
    /**
     * Parses response received from OAuth2 server.
     * NOTE: this is Oauth2-client specific.
     *
     * @param string $response
     */
    abstract public function wrap(string $response): void;
    
    /**
     * Gets parsed response
     *
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}
