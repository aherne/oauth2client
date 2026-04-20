<?php

namespace Lucinda\OAuth2\DriverRequest;

use Lucinda\OAuth2\Client\Information as ClientInformation;
use Lucinda\OAuth2\Server\Information as ServerInformation;
use Lucinda\OAuth2\AuthorizationCode\Request as AuthorizationCodeRequest;
use Lucinda\OAuth2\RedirectionExecutor;

class AuthorizationCodeRequestWrapper
{
    private string $redirectURL;

    public function __construct(ClientInformation $clientInformation, ServerInformation $serverInformation, string $state="")
    {
        $this->execute($clientInformation, $serverInformation, $state);
    }

    private function execute(ClientInformation $clientInformation, ServerInformation $serverInformation, string $state): void
    {
        $executor = new RedirectionExecutor();
        $acr = new AuthorizationCodeRequest($serverInformation->getAuthorizationUrl());
        $acr->setClientInformation($clientInformation);
        $acr->setRedirectURL($clientInformation->getSiteURL());
        $acr->setScope(implode(" ", $serverInformation->getScopes()));
        if ($state) {
            $acr->setState($state);
        }
        $acr->execute($executor);
        $this->redirectURL = $executor->getRedirectURL();
    }

    public function getResponse(): string
    {
        return $this->redirectURL;
    }
}
