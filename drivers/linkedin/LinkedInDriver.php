<?php
namespace OAuth2;

require_once("LinkedInResponseWrapper.php");

/**
 * Implements LinkedIn OAuth2 driver on top of Driver architecture
 */
class LinkedInDriver extends Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://www.linkedin.com/oauth/v2/authorization";
    const TOKEN_ENDPOINT_URL = "https://www.linkedin.com/oauth/v2/accessToken";
    
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
        return new LinkedinResponseWrapper();
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
        $we->addHeader("Authorization", "Bearer ".$accessToken);
        $we->addHeader("x-li-format", "json");
        $parameters = (!empty($fields)?array("fields"=>implode(",", $fields)):array());
        $we->execute($resourceURL, $parameters);
        return $responseWrapper->getResponse();
    }
}
