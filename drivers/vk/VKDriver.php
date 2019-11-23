<?php
namespace Lucinda\OAuth2;

require("VKResponseWrapper.php");

/**
 * Implements VK OAuth2 driver on top of Driver architecture
 */
class VKDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://oauth.vk.com/authorize";
    const TOKEN_ENDPOINT_URL = "https://oauth.vk.com/access_token";
        
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
        return new VKResponseWrapper();
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
        $fields["access_token"] = $accessToken;
        $we->execute($resourceURL, $fields);
        return $responseWrapper->getResponse();
    }
}
