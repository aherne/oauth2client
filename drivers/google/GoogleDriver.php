<?php
namespace Lucinda\OAuth2;

require("GoogleResponseWrapper.php");

/**
 * Implements Google OAuth2 driver on top of Driver architecture
 */
class GoogleDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://accounts.google.com/o/oauth2/auth";
    const TOKEN_ENDPOINT_URL = "https://accounts.google.com/o/oauth2/token";

    /**
     * Gets OAuth2 server information.
     *
     * @return ServerInformation
     */
    protected function getServerInformation()
    {
        return new ServerInformation(self::AUTHORIZATION_ENDPOINT_URL, self::TOKEN_ENDPOINT_URL);
    }
    
    /**
     * Gets OAuth2 server response parser.
     *
     * @return ResponseWrapper
     */
    protected function getResponseWrapper()
    {
        return new GoogleResponseWrapper();
    }
}
