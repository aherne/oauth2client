<?php
namespace Lucinda\OAuth2\Vendor\LinkedIn;

use \Lucinda\OAuth2\Server\Information as ServerInformation;
use \Lucinda\OAuth2\ResponseWrapper;
use \Lucinda\OAuth2\Server\Exception as ServerException;
use \Lucinda\OAuth2\Client\Exception as ClientException;
use \Lucinda\OAuth2\WrappedExecutor;
use Lucinda\URL\FileNotFoundException;
use Lucinda\URL\Request\Method;

/**
 * Implements LinkedIn OAuth2 driver on top of \Lucinda\OAuth2\Driver architecture
 */
class Driver extends \Lucinda\OAuth2\Driver
{
    const AUTHORIZATION_ENDPOINT_URL = "https://www.linkedin.com/oauth/v2/authorization";
    const TOKEN_ENDPOINT_URL = "https://www.linkedin.com/oauth/v2/accessToken";
    
    /**
     * Gets OAuth2 server information.
     *
     * @return ServerInformation
     */
    protected function getServerInformation(): ServerInformation
    {
        return new ServerInformation(self::AUTHORIZATION_ENDPOINT_URL, self::TOKEN_ENDPOINT_URL);
    }
    
    /**
     * Gets OAuth2 server response parser.
     *
     * @return ResponseWrapper
     */
    protected function getResponseWrapper(): ResponseWrapper
    {
        return new \Lucinda\OAuth2\Vendor\LinkedIn\ResponseWrapper();
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
     * @throws FileNotFoundException
     */
    public function getResource(string $accessToken, string $resourceURL, array $fields=array()): array
    {
        $responseWrapper = $this->getResponseWrapper();
        $we = new WrappedExecutor($responseWrapper);
        $we->setHttpMethod(Method::GET);
        $we->addHeader("Authorization", "Bearer ".$accessToken);
        $we->addHeader("x-li-format", "json");
        $parameters = (!empty($fields)?array("fields"=>implode(",", $fields)):array());
        $we->execute($resourceURL, $parameters);
        return $responseWrapper->getResponse();
    }
}
