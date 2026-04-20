<?php

namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Client\Information as ClientInformation;
use Lucinda\OAuth2\Client\Exception as ClientException;
use Lucinda\OAuth2\AccessToken\Response as AccessTokenResponse;
use Lucinda\OAuth2\DriverRequest\AccessTokenRequestWrapper;
use Lucinda\OAuth2\DriverRequest\AuthorizationCodeRequestWrapper;
use Lucinda\OAuth2\DriverRequest\RefreshTokenRequestWrapper;
use Lucinda\OAuth2\Server\Information as ServerInformation;
use Lucinda\OAuth2\UserInfo\Requester as UserInfoRequester;
use Lucinda\OAuth2\UserInfo\Extractor as UserInfoExtractor;

/**
 * Encapsulates operations one can perform on an OAuth2 provider. Acts like a single entry point that hides OAuth2
 * providers complexity. For each provider, you will have to implement a class that extends Driver and implements
 * abstract protected functions.
 */
abstract class Driver
{
    protected ClientInformation $clientInformation;
    protected ServerInformation $serverInformation;
    protected ResponseWrapper $responseWrapper;

    /**
     * Creates an object
     *
     * @param ClientInformation $clientInformation Encapsulates information about OAuth2 client application
     * @param ServerInformation $serverInformation Encapsulates information about OAuth2 driver endpoints
     */
    public function __construct(ClientInformation $clientInformation, ServerInformation $serverInformation)
    {
        $this->clientInformation = $clientInformation;
        $this->serverInformation = $serverInformation;
        $this->responseWrapper = $this->getResponseWrapper();
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
        $object = new AuthorizationCodeRequestWrapper($this->clientInformation, $this->serverInformation, $state);
        return $object->getResponse();
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
        $object = new AccessTokenRequestWrapper($this->clientInformation, $this->serverInformation, $this->responseWrapper, $authorizationCode);
        return $object->getResponse();
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
        $object = new RefreshTokenRequestWrapper($this->clientInformation, $this->serverInformation, $this->responseWrapper, $refreshToken);
        return $object->getResponse();
    }

    public function getUserInfo(string $accessToken): UserInfo
    {
        $requester = $this->getUserInfoRequester();
        $data = $requester->request($this->serverInformation, $this->responseWrapper, $accessToken);
        $extractor = $this->getUserInfoExtractor();
        return $extractor->convert($data);
    }

    abstract protected function getResponseWrapper(): ResponseWrapper;

    abstract protected function getUserInfoRequester(): UserInfoRequester;

    abstract protected function getUserInfoExtractor(): UserInfoExtractor;
}
