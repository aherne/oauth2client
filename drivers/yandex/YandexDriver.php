<?php
namespace Lucinda\OAuth2;

require("YandexResponseWrapper.php");

/**
 * Implements Yandex OAuth2 driver on top of Driver architecture.
 */
class YandexDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://oauth.yandex.com/authorize";
    const TOKEN_ENDPOINT_URL = "https://oauth.yandex.com/token";
    
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
        return new YandexResponseWrapper();
    }
}
