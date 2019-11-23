<?php
namespace Lucinda\OAuth2;

/**
 * Encapsulates an access token regeneration request based on refresh token according to RFC6749
 */
class RefreshTokenRequest implements Request
{
    protected $endpointURL;
    protected $clientInformation;
    protected $redirectURL;
    protected $refreshToken;
    
    /**
     * (Mandatory) Sets URL of access token endpoint @ Oauth2 Server
     *
     * @param string $endpointURL
     */
    public function __construct($endpointURL)
    {
        $this->endpointURL = $endpointURL;
    }
    
    /**
     * (Mandatory) Sets refresh token already obtained.
     *
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }
    
    /**
     * (Mandatory) Sets client information.
     *
     * @param ClientInformation $clientInformation
     */
    public function setClientInformation(ClientInformation $clientInformation)
    {
        $this->clientInformation = $clientInformation;
    }
    
    /**
     * (Optional) Sets callback redirect URL to send access token to.
     *
     * @param string $redirectURL
     */
    public function setRedirectURL($redirectURL)
    {
        $this->redirectURL = $redirectURL;
    }
    
    /**
     * Executes request.
     *
     * @param RequestExecutor $executor Performs request execution.
     * @throws ClientException If insufficient parameters are supplied to issue a request.
     */
    public function execute(RequestExecutor $executor)
    {
        if (!$this->clientInformation || !$this->clientInformation->getApplicationID()) {
            throw new ClientException("Client ID is required for access token requests!");
        }
        if (!$this->refreshToken) {
            throw new ClientException("Refresh token is required for access token regeneration!");
        }
        $parameters = array();
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
