<?php

namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Client\Information as ClientInformation;
use Lucinda\OAuth2\Client\Exception as ClientException;
use Lucinda\OAuth2\AuthorizationCode\Request as AuthorizationCodeRequest;
use Lucinda\OAuth2\AccessToken\Request as AccessTokenRequest;
use Lucinda\OAuth2\AccessToken\Response as AccessTokenResponse;
use Lucinda\OAuth2\RefreshToken\Request as RefreshTokenRequest;
use Lucinda\OAuth2\Server\Exception as ServerException;
use Lucinda\OAuth2\Server\Information as ServerInformation;
use Lucinda\URL\FileNotFoundException;
use Lucinda\URL\Request\Method;

/**
 * Encapsulates operations one can perform on an OAuth2 provider. Acts like a single entry point that hides OAuth2
 * providers complexity. For each provider, you will have to implement a class that extends Driver and implements
 * abstract protected functions.
 */
abstract class Driver
{
    protected ClientInformation $clientInformation;
    protected ServerInformation $serverInformation;
    /**
     * @var string[]
     */
    protected array $scopes = [];

    /**
     * Creates an object
     *
     * @param ClientInformation $clientInformation Encapsulates information about OAuth2 client application
     * @param string[]          $scopes            Scopes of authorization code request.
     */
    public function __construct(ClientInformation $clientInformation, array $scopes = [])
    {
        $this->clientInformation = $clientInformation;
        $this->serverInformation = $this->getServerInformation();
        $this->scopes = $scopes;
    }

    /**
     * Gets authorization code endpoint URL.
     *
     * @param  string $state Any client state that needs to be passed on to the client request URI.
     * @return string Full authorization code endpoint URL.
     * @throws ClientException When client fails to provide mandatory parameters.
     */
    public function getAuthorizationCodeEndpoint(string $state=""): string
    {
        $executor = new RedirectionExecutor();
        $acr = new AuthorizationCodeRequest($this->serverInformation->getAuthorizationEndpoint());
        $acr->setClientInformation($this->clientInformation);
        $acr->setRedirectURL($this->clientInformation->getSiteURL());
        $acr->setScope(implode(" ", $this->scopes));
        if ($state) {
            $acr->setState($state);
        }
        $acr->execute($executor);
        return $executor->getRedirectURL();
    }

    /**
     * Gets access token necessary to retrieve resources with.
     *
     * @param  string $authorizationCode Authorization code received from OAuth2 provider
     * @return AccessTokenResponse Access token response.
     * @throws ClientException When client fails to provide mandatory parameters.
     */
    public function getAccessToken(string $authorizationCode): AccessTokenResponse
    {
        $responseWrapper = $this->getResponseWrapper();
        $wrappedExecutor = new WrappedExecutor($responseWrapper);
        $wrappedExecutor->setHttpMethod(Method::POST);
        $wrappedExecutor->addHeader("Content-Type", "application/x-www-form-urlencoded");
        $atr = new AccessTokenRequest($this->serverInformation->getTokenEndpoint());
        $atr->setClientInformation($this->clientInformation);
        $atr->setCode($authorizationCode);
        $atr->setRedirectURL($this->clientInformation->getSiteURL());
        $atr->execute($wrappedExecutor);
        return new AccessTokenResponse($responseWrapper->getResponse());
    }

    /**
     * Regenerates access token based on access token already obtained and stored
     *
     * @param  string $refreshToken Refresh token to use in regenerating
     * @return AccessTokenResponse Access token response.
     * @throws ClientException When client fails to provide mandatory parameters.
     */
    public function refreshAccessToken(string $refreshToken): AccessTokenResponse
    {
        $responseWrapper = $this->getResponseWrapper();
        $wrappedExecutor = new WrappedExecutor($responseWrapper);
        $wrappedExecutor->setHttpMethod(Method::POST);
        $wrappedExecutor->addHeader("Content-Type", "application/x-www-form-urlencoded");
        $atr = new RefreshTokenRequest($this->serverInformation->getTokenEndpoint());
        $atr->setClientInformation($this->clientInformation);
        $atr->setRefreshToken($refreshToken);
        $atr->setRedirectURL($this->clientInformation->getSiteURL());
        $atr->execute($wrappedExecutor);
        return new AccessTokenResponse($responseWrapper->getResponse());
    }

    /**
     * Gets remote resource based on access token
     *
     * @param  string   $accessToken OAuth2 access token
     * @param  string   $resourceURL URL of remote resource`
     * @param  string[] $fields      Fields to retrieve from remote resource.
     * @return array<mixed>
     * @throws ClientException When client fails to provide mandatory parameters.
     * @throws ServerException|FileNotFoundException When server responds with an error.
     */
    public function getResource(string $accessToken, string $resourceURL, array $fields=[]): array
    {
        $responseWrapper = $this->getResponseWrapper();
        $wrappedExecutor = new WrappedExecutor($responseWrapper);
        $wrappedExecutor->setHttpMethod(Method::GET);
        $wrappedExecutor->addHeader("Authorization", "Bearer ".$accessToken);
        $parameters = (!empty($fields) ? array("fields"=>implode(",", $fields)) : []);
        $wrappedExecutor->execute($resourceURL, $parameters);
        return $responseWrapper->getResponse();
    }

    /**
     * Gets OAuth2 server information.
     *
     * @return ServerInformation
     */
    abstract protected function getServerInformation(): ServerInformation;

    /**
     * Gets OAuth2 server response parser.
     *
     * @return ResponseWrapper
     */
    abstract protected function getResponseWrapper(): ResponseWrapper;
}
