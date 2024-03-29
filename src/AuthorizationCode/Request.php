<?php

namespace Lucinda\OAuth2\AuthorizationCode;

use Lucinda\OAuth2\Client\Information as ClientInformation;
use Lucinda\OAuth2\RequestExecutor;
use Lucinda\OAuth2\Client\Exception as ClientException;

/**
 * Encapsulates an authorization code request according to RFC6749
 */
class Request implements \Lucinda\OAuth2\Request
{
    protected string $endpointURL;
    protected ?ClientInformation $clientInformation = null;
    protected ?string $redirectURL = null;
    protected ?string $scope = null;
    protected ?string $state = null;

    /**
     * (Mandatory) Sets URL of authorization code endpoint @ Oauth2 Server
     *
     * @param string $endpointURL
     */
    public function __construct(string $endpointURL)
    {
        $this->endpointURL = $endpointURL;
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
     * Sets callback redirect URL to send code to.
     *
     * @param string $redirectURL
     */
    public function setRedirectURL(string $redirectURL): void
    {
        $this->redirectURL = $redirectURL;
    }

    /**
     * Sets scope of access request.
     *
     * @param string $scope
     */
    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * Sets opaque value used by the client to maintain state between the request and callback
     *
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
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
            throw new ClientException("Client ID is required for authorization code requests!");
        }
        $parameters = [];
        $parameters["response_type"] = "code";
        $parameters["client_id"] = $this->clientInformation->getApplicationID();
        if ($this->redirectURL) {
            $parameters["redirect_uri"] = $this->redirectURL;
        }
        if ($this->scope) {
            $parameters["scope"] = $this->scope;
        }
        if ($this->state) {
            $parameters["state"] = $this->state;
        }
        $executor->execute($this->endpointURL, $parameters);
    }
}
