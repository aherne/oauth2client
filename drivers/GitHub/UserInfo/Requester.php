<?php

namespace Lucinda\OAuth2\Vendor\GitHub\UserInfo;

use Lucinda\OAuth2\ResponseWrapper;
use Lucinda\OAuth2\Server\Information as ServerInformation;
use Lucinda\OAuth2\WrappedExecutor;
use Lucinda\URL\Request\Method;
use Lucinda\OAuth2\UserInfo\Requester as RequesterInterface;

class Requester implements RequesterInterface
{
    public function request(ServerInformation $serverInformation, ResponseWrapper $responseWrapper, string $accessToken): array
    {
        $resourceURLs = $serverInformation->getResourceURL();
        
        $response = [];
        foreach ($resourceURLs as $resourceURL) {
            $wrappedExecutor = new WrappedExecutor($responseWrapper);
            $wrappedExecutor->setHttpMethod(Method::GET);
            $wrappedExecutor->addHeader("Authorization", "Bearer ".$accessToken);
            $wrappedExecutor->addHeader("Accept","application/vnd.github+json");
            $wrappedExecutor->execute($resourceURL, []);
            $response[] = $responseWrapper->getResponse();
        }
        return $response;
    }
}