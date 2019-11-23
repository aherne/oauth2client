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
        return new VKResponseWrapper();
    }
        
    /**
     * {@inheritDoc}
     * @see Driver::getResource()
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
