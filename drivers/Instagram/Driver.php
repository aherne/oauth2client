<?php
namespace Lucinda\OAuth2\Vendor\Instagram;

use \Lucinda\OAuth2\Server\ServerInformation;
use \Lucinda\OAuth2\ResponseWrapper;
use \Lucinda\OAuth2\Server\ServerException;
use \Lucinda\OAuth2\Client\ClientException;
use \Lucinda\OAuth2\AccessToken\AccessTokenRequest;
use \Lucinda\OAuth2\AccessToken\AccessTokenResponse;
use \Lucinda\OAuth2\WrappedExecutor;
use \Lucinda\OAuth2\HttpMethod;

/**
 * Implements Instagram OAuth2 driver on top of \Lucinda\OAuth2\Driver architecture
 */
class Driver extends \Lucinda\OAuth2\Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://api.instagram.com/oauth/authorize/";
    const TOKEN_ENDPOINT_URL = "https://api.instagram.com/oauth/access_token";
    
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
        return new \Lucinda\OAuth2\Vendor\Instagram\ResponseWrapper();
    }
    
    /**
     * Gets access token necessary to retrieve resources with.
     *
     * @param string $authorizationCode Authorization code received from OAuth2 provider
     * @return \Lucinda\OAuth2\AccessToken\AccessTokenResponse Access token response.
     * @throws ClientException When client fails to provide mandatory parameters.
     * @throws ServerException When server responds with an error.
     */
    public function getAccessToken(string $authorizationCode): AccessTokenResponse
    {
        $responseWrapper = $this->getResponseWrapper();
        $we = new WrappedExecutor($responseWrapper);
        $we->setHttpMethod(HttpMethod::POST);
        $we->addHeader("Content-Type", "application/x-www-form-urlencoded");
        $atr = new AccessTokenRequest($this->serverInformation->getTokenEndpoint());
        $atr->setClientInformation($this->clientInformation);
        $atr->setCode($authorizationCode);
        $atr->setRedirectURL($this->clientInformation->getSiteURL());
        $atr->execute($we);
        $response = $responseWrapper->getResponse();
        if (!empty($response["error_message"])) {
            throw new ServerException($response["error_message"]);
        }
        return new AccessTokenResponse($response);
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
        $fields["client_id"] = $this->clientInformation->getApplicationID();
        $fields["access_token"] = $accessToken;
        $we->execute($resourceURL, $fields);
        return $responseWrapper->getResponse();
    }
}
