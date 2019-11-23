<?php
namespace Lucinda\OAuth2;

require("InstagramResponseWrapper.php");

/**
 * Implements Instagram OAuth2 driver on top of Driver architecture
 */
class InstagramDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://api.instagram.com/oauth/authorize/";
    const TOKEN_ENDPOINT_URL = "https://api.instagram.com/oauth/access_token";
    
    /**
     * Gets OAuth2 server information.
     *
     * @return ServerInformation
     */
    protected function getServerInformation()
    {
        return new ServerInformation(self::AUTHORIZATION_ENDPOINT_URL, self::TOKEN_ENDPOINT_URL);
    }

    /**
     * Gets OAuth2 server response parser.
     *
     * @return ResponseWrapper
     */
    protected function getResponseWrapper()
    {
        return new InstagramResponseWrapper();
    }
        
    /**
     * {@inheritDoc}
     * @see Driver::getAccessToken()
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
    public function getResource($accessToken, $resourceURL, $fields=array())
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
