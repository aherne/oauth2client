<?php
namespace Lucinda\OAuth2\Vendor\Yahoo;

use \Lucinda\OAuth2\Server\ServerInformation;
use \Lucinda\OAuth2\ResponseWrapper;
use \Lucinda\OAuth2\Server\ServerException;
use \Lucinda\OAuth2\Client\ClientException;
use \Lucinda\OAuth2\WrappedExecutor;
use \Lucinda\OAuth2\HttpMethod;

/**
 * Implements Yahoo OAuth2 driver on top of \Lucinda\OAuth2\Driver architecture
 */
class Driver extends \Lucinda\OAuth2\Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://api.login.yahoo.com/oauth2/request_auth";
    const TOKEN_ENDPOINT_URL = "https://api.login.yahoo.com/oauth2/get_token";
    
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
        return new \Lucinda\OAuth2\Vendor\Yahoo\ResponseWrapper();
    }
    
    /**
     * Gets remote resource based on access token
     *
     * @param string $accessToken OAuth2 access token
     * @param string $resourceURL URL of remote resource`
     * @param string[] $fields Fields to retrieve from remote resource.
     * @return array
     * @throws ClientException When client fails to provide mandatory parameters.
     * @throws ServerException When server responds with an error.
     */
    public function getResource(string $accessToken, string $resourceURL, array $fields=array()): array
    {
        $responseWrapper = $this->getResponseWrapper();
        $we = new WrappedExecutor($responseWrapper);
        $we->setHttpMethod(HttpMethod::GET);
        $we->addHeader("Authorization", "Bearer ".$accessToken);
        $we->execute($resourceURL, array_merge($fields, array("format"=>"json")));
        return $responseWrapper->getResponse();
    }
}
