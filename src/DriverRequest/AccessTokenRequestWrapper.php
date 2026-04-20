<?php

namespace Lucinda\OAuth2\DriverRequest;

use Lucinda\OAuth2\ResponseWrapper;
use Lucinda\OAuth2\AccessToken\Request as AccessTokenRequest;
use Lucinda\OAuth2\AccessToken\Response as AccessTokenResponse;
use Lucinda\OAuth2\Client\Information as ClientInformation;
use Lucinda\OAuth2\Server\Information as ServerInformation;
use Lucinda\OAuth2\WrappedExecutor;
use Lucinda\URL\Request\Method;

class AccessTokenRequestWrapper
{
    private AccessTokenResponse $accessTokenResponse;

    public function __construct(ClientInformation $clientInformation, ServerInformation $serverInformation, ResponseWrapper $responseWrapper, string $authorizationCode)
    {
        $this->execute($clientInformation, $serverInformation, $responseWrapper, $authorizationCode);
    }

    private function execute(ClientInformation $clientInformation, ServerInformation $serverInformation, ResponseWrapper $responseWrapper, string $authorizationCode): void
    {
        $wrappedExecutor = new WrappedExecutor($responseWrapper);
        $wrappedExecutor->setHttpMethod(Method::POST);
        $wrappedExecutor->addHeader("Content-Type", "application/x-www-form-urlencoded");
        $atr = new AccessTokenRequest($serverInformation->getAccessTokenUrl());
        $atr->setClientInformation($clientInformation);
        $atr->setCode($authorizationCode);
        $atr->setRedirectURL($clientInformation->getSiteURL());
        $atr->execute($wrappedExecutor);
        $this->accessTokenResponse =  new AccessTokenResponse($responseWrapper->getResponse());
    }

    public function getResponse(): AccessTokenResponse
    {
        return $this->accessTokenResponse;
    }
}