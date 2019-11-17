<?php
namespace OAuth2;

require("GitHubResponseWrapper.php");

/**
 * Implements GitHub OAuth2 driver on top of Driver architecture
 */
class GitHubDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://github.com/login/oauth/authorize";
    const TOKEN_ENDPOINT_URL = "https://github.com/login/oauth/access_token";
    
    private $appName;
    
    /**
     * {@inheritDoc}
     * @see Driver::getServerInformation()
     */
    protected function getServerInformation()
    {
        return new ServerInformation(self::AUTHORIZATION_ENDPOINT_URL, self::TOKEN_ENDPOINT_URL);
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::getResponseWrapper()
     */
    protected function getResponseWrapper()
    {
        return new GithubResponseWrapper();
    }
    
    /**
     * Sets name of remote GitHub application. MANDATORY for resources retrieval!
     *
     * @param string $applicationName
     */
    public function setApplicationName($applicationName)
    {
        $this->appName = $applicationName;
    }
    
    /**
     * {@inheritDoc}
     * @see Driver::getResource()
     */
    public function getResource($accessToken, $resourceURL, $fields=array())
    {
        if (!$this->appName) {
            throw new ClientException("Setting application name is mandatory to retrieve GitHub resources!");
        }
        $responseWrapper = $this->getResponseWrapper();
        $we = new WrappedExecutor($responseWrapper);
        $we->setHttpMethod(HttpMethod::GET);
        $we->addHeader("Authorization", "token ".$accessToken);
        $we->setUserAgent($this->appName);
        $parameters = (!empty($fields)?array("fields"=>implode(",", $fields)):array());
        $we->execute($resourceURL, $parameters);
        return $responseWrapper->getResponse();
    }
}
