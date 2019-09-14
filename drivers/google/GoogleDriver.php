<?php
namespace OAuth2;

require_once("GoogleResponseWrapper.php");

/**
 * Implements Google OAuth2 driver on top of Driver architecture
 */
class GoogleDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://accounts.google.com/o/oauth2/auth";
    const TOKEN_ENDPOINT_URL = "https://accounts.google.com/o/oauth2/token";

    /**
     * {@inheritDoc}
     * @see Driver::getServerInformation()
     */
    protected function getServerInformation()
    {
        return new ServerInformation(self::AUTHORIZATION_ENDPOINT_URL, self::TOKEN_ENDPOINT_URL);
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::getResponseWrapper()
     */
    protected function getResponseWrapper()
    {
        return new GoogleResponseWrapper();
    }
}
