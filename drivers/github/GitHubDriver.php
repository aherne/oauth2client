<?php
namespace Lucinda\OAuth2;

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
