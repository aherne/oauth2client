<?php
namespace Lucinda\OAuth2;

require("YahooResponseWrapper.php");

/**
 * Implements Yahoo OAuth2 driver on top of Driver architecture
 */
class YahooDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://api.login.yahoo.com/oauth2/request_auth";
    const TOKEN_ENDPOINT_URL = "https://api.login.yahoo.com/oauth2/get_token";
    
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
        return new YahooResponseWrapper();
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::getResource()
     */
    public function getResource($accessToken, $resourceURL, $fields=array())
    {
        $responseWrapper = $this->getResponseWrapper();
        $we = new WrappedExecutor($responseWrapper);
        $we->setHttpMethod(HttpMethod::GET);
        $we->addHeader("Authorization", "Bearer ".$accessToken);
        $we->execute($resourceURL, array_merge($fields, array("format"=>"json")));
        return $responseWrapper->getResponse();
    }
}
