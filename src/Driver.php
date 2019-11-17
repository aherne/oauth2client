<?php
namespace OAuth2;

require("ClientInformation.php");
require("ServerInformation.php");
require("ClientException.php");
require("ServerException.php");
require("Request.php");
require("AuthorizationCodeRequest.php");
require("AuthorizationCodeResponse.php");
require("AccessTokenRequest.php");
require("RefreshTokenRequest.php");
require("AccessTokenResponse.php");
require("RequestExecutor.php");
require("RequestExecutors/RedirectionExecutor.php");
require("RequestExecutors/WrappedExecutor.php");
require("ResponseWrapper.php");

/**
 * Encapsulates operations one can perform on an OAuth2 provider. Acts like a single entry point that hides OAuth2 providers complexity. For each provider,
 * you will have to implement a class that extends Driver and implements abstract protected functions.
 */
abstract class Driver
{
    protected $clientInformation;
    protected $serverInformation;

    /**
     * Creates an object
     *
     * @param ClientInformation $clientInformation Encapsulates information about OAuth2 client application
     */
    public function __construct(ClientInformation $clientInformation)
    {
        $this->clientInformation = $clientInformation;
        $this->serverInformation = $this->getServerInformation();
    }

    /**
     * Gets authorization code endpoint URL.
     *
     * @param string[] $scopes Scopes to use in access request.
     * @param string $state Any client state that needs to be passed on to the client request URI.
     * @return string Full authorization code endpoint URL.
     * @throws ClientException When client fails to provide mandatory parameters.
     */
    public function getAuthorizationCodeEndpoint($scopes, $state="")
    {
        $executor = new RedirectionExecutor();
        $acr = new AuthorizationCodeRequest($this->serverInformation->getAuthorizationEndpoint());
        $acr->setClientInformation($this->clientInformation);
        $acr->setRedirectURL($this->clientInformation->getSiteURL());
        $acr->setScope(implode(" ", $scopes));
        if ($state) {
            $acr->setState($state);
        }
        $acr->execute($executor);
        return $executor->getRedirectURL();
    }

    /**
     * Gets access token necessary to retrieve resources with.
     *
     * @param string $authorizationCode Authorization code received from OAuth2 provider
     * @return AccessTokenResponse Access token response.
     * @throws ClientException When client fails to provide mandatory parameters.
     * @throws ServerException When server responds with an error.
     */
    public function getAccessToken($authorizationCode)
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
        return new AccessTokenResponse($responseWrapper->getResponse());
    }
    
    /**
     * Regenerates access token based on access token already obtained and stored
     *
     * @param string $refreshToken Refresh token to use in regenerating
     * @return AccessTokenResponse Access token response.
     * @throws ClientException When client fails to provide mandatory parameters.
     * @throws ServerException When server responds with an error.
     */
    public function refreshAccessToken($refreshToken)
    {
        $responseWrapper = $this->getResponseWrapper();
        $we = new WrappedExecutor($responseWrapper);
        $we->setHttpMethod(HttpMethod::POST);
        $we->addHeader("Content-Type", "application/x-www-form-urlencoded");
        $atr = new RefreshTokenRequest($this->serverInformation->getTokenEndpoint());
        $atr->setClientInformation($this->clientInformation);
        $atr->setRefreshToken($refreshToken);
        $atr->setRedirectURL($this->clientInformation->getSiteURL());
        $atr->execute($we);
        return new AccessTokenResponse($responseWrapper->getResponse());
    }

    /**
     * Gets remote resource based on access token
     *
     * @param string $accessToken OAuth2 access token
     * @param string $resourceURL URL of remote resource`
     * @param string[] $fields Fields to retrieve from remote resource.
     * @return mixed
     * @throws ClientException When client fails to provide mandatory parameters.
     * @throws ServerException When server responds with an error.
     */
    public function getResource($accessToken, $resourceURL, $fields=array())
    {
        $responseWrapper = $this->getResponseWrapper();
        $we = new WrappedExecutor($responseWrapper);
        $we->setHttpMethod(HttpMethod::GET);
        $we->addHeader("Authorization", "Bearer ".$accessToken);
        $parameters = (!empty($fields)?array("fields"=>implode(",", $fields)):array());
        $we->execute($resourceURL, $parameters);
        return $responseWrapper->getResponse();
    }
    
    /**
     * Gets OAuth2 server information.
     *
     * @return ServerInformation
     */
    abstract protected function getServerInformation();
    
    /**
     * Gets OAuth2 server response parser.
     *
     * @return ResponseWrapper
     */
    abstract protected function getResponseWrapper();
}
