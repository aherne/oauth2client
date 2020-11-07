<?php
namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Client\Exception as ClientException;
use Lucinda\OAuth2\Server\Exception as ServerException;

/**
 * Implements an executor on top of cURL implementing OAuth2 request execution rules in accordance to RFC6749.
 */
class WrappedExecutor implements RequestExecutor
{
    protected $responseWrapper;
    protected $httpMethod;
    protected $headers = array();
    protected $userAgent;
    
    /**
     * Saves object received to be used in issuing requests to OAuth2 vendor
     *
     * @param ResponseWrapper $responseWrapper
     */
    public function __construct(ResponseWrapper $responseWrapper)
    {
        $this->responseWrapper = $responseWrapper;
    }
    
    /**
     * Sets request http method
     *
     * @param string $httpMethod
     */
    public function setHttpMethod(string $httpMethod = HttpMethod::POST): void
    {
        $this->httpMethod = $httpMethod;
    }
    
    /**
     * Sets request user agent
     *
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }
    
    /**
     * Adds header to request
     *
     * @param string $name
     * @param string $value
    */
    public function addHeader(string $name, string $value): void
    {
        $this->headers[] = $name.": ".$value;
    }
    
    /**
     * Executes request
     *
     * @param string $url OAuth2 server endpoint url.
     * @param array $parameters Associative array of parameters to send.
     */
    public function execute(string $url, array $parameters): void
    {
        try {
            $request = new \Lucinda\URL\Request();
            $request->setMethod($this->httpMethod);
            if ($this->httpMethod==HttpMethod::POST) {
                $request->setParameters($parameters);
                $request->setURL($url);
            } else {
                $request->setURL($url.($parameters?"?".http_build_query($parameters):""));
            }
            $headers = $request->setHeaders($this->headers);
            if ($this->userAgent) {
                $headers->setUserAgent($this->userAgent);
            }
            $response = $request->execute();
            $this->responseWrapper->wrap($response->getBody());
        } catch (\Lucinda\URL\Request\Exception $e) {
            throw new ClientException($e->getMessage());
        } catch (\Lucinda\URL\Response\Exception $e) {
            throw new ServerException($e->getMessage(), $e->getCode());
        }
    }
}
