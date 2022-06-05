<?php

namespace Lucinda\OAuth2\RefreshToken;

use Lucinda\OAuth2\Client\Information as ClientInformation;
use Lucinda\OAuth2\RequestExecutor;
use Lucinda\OAuth2\Client\Exception as ClientException;

/**
 * Encapsulates an access token regeneration request based on refresh token according to RFC6749
 */
class Request implements \Lucinda\OAuth2\Request
{
    protected string $endpointURL;
    protected ?ClientInformation $clientInformation;
    protected ?string $redirectURL;
    protected ?string $refreshToken;

    /**
     * (Mandatory) Sets URL of access token endpoint @ Oauth2 Server
     *
     * @param string $endpointURL
     */
    public function __construct(string $endpointURL)
    {
        $this->endpointURL = $endpointURL;
    }

    /**
     * (Mandatory) Sets refresh token already obtained.
     *
     * @param string $refreshToken
     */
    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * (Mandatory) Sets client information.
     *
     * @param ClientInformation $clientInformation
     */
    public function setClientInformation(ClientInformation $clientInformation): void
    {
        $this->clientInformation = $clientInformation;
    }

    /**
     * (Optional) Sets callback redirect URL to send access token to.
     *
     * @param string $redirectURL
     */
    public function setRedirectURL(string $redirectURL): void
    {
        $this->redirectURL = $redirectURL;
    }

    /**
     * Executes request.
     *
     * @param  RequestExecutor $executor Performs request execution.
     * @throws ClientException If insufficient parameters are supplied to issue a request.
     */
    public function execute(RequestExecutor $executor): void
    {
        if (!$this->clientInformation || !$this->clientInformation->getApplicationID()) {
            throw new ClientException("Client ID is required for access token requests!");
        }
        if (!$this->refreshToken) {
            throw new ClientException("Refresh token is required for access token regeneration!");
        }
        $parameters = [];
        $parameters["grant_type"] = "refresh_token";
        $parameters["client_id"] = $this->clientInformation->getApplicationID();
        $parameters["refresh_token"] = $this->refreshToken;
        if ($this->clientInformation->getApplicationSecret()) {
            $parameters["client_secret"] = $this->clientInformation->getApplicationSecret();
        }
        if ($this->redirectURL) {
            $parameters["redirect_uri"] = $this->redirectURL;
        }
        $executor->execute($this->endpointURL, $parameters);
    }
}
