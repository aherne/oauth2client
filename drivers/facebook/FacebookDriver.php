<?php
namespace Lucinda\OAuth2;

require("FacebookResponseWrapper.php");

/**
 * Implements Facebook OAuth2 driver on top of Driver architecture
 */
class FacebookDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://www.facebook.com/v2.8/dialog/oauth";
    const TOKEN_ENDPOINT_URL = "https://graph.facebook.com/v2.8/oauth/access_token";
    
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
        return new FacebookResponseWrapper();
    }
}
