<?php
namespace Lucinda\OAuth2\Vendor\Google;

use \Lucinda\OAuth2\Server\Information as ServerInformation;
use \Lucinda\OAuth2\ResponseWrapper;

/**
 * Implements Google OAuth2 driver on top of \Lucinda\OAuth2\Driver architecture
 */
class Driver extends \Lucinda\OAuth2\Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://accounts.google.com/o/oauth2/auth";
    const TOKEN_ENDPOINT_URL = "https://accounts.google.com/o/oauth2/token";

    /**
     * Gets OAuth2 server information.
     *
     * @return ServerInformation
     */
    protected function getServerInformation(): ServerInformation
    {
        return new ServerInformation(self::AUTHORIZATION_ENDPOINT_URL, self::TOKEN_ENDPOINT_URL);
    }
    
    /**
     * Gets OAuth2 server response parser.
     *
     * @return ResponseWrapper
     */
    protected function getResponseWrapper(): ResponseWrapper
    {
        return new \Lucinda\OAuth2\Vendor\Google\ResponseWrapper();
    }
}
