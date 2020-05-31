<?php
namespace Lucinda\OAuth2\Vendor\Yandex;

use \Lucinda\OAuth2\Server\Information as ServerInformation;
use \Lucinda\OAuth2\ResponseWrapper;

/**
 * Implements Yandex OAuth2 driver on top of \Lucinda\OAuth2\Driver architecture.
 */
class Driver extends \Lucinda\OAuth2\Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://oauth.yandex.com/authorize";
    const TOKEN_ENDPOINT_URL = "https://oauth.yandex.com/token";
    
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
        return new \Lucinda\OAuth2\Vendor\Yandex\ResponseWrapper();
    }
}
