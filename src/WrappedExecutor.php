<?php
namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Client\Exception as ClientException;

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
    public function __construct(ResponseWrapper $responseWrapper): void
    {
        $this->responseWrapper = $responseWrapper;
    }
    
    /**
     * Sets request http method
     *
     * @param integer $httpMethod
     */
    public function setHttpMethod(int $httpMethod = HttpMethod::POST): void
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
        $ch = curl_init();
        switch ($this->httpMethod) {
            case HttpMethod::POST:
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
                break;
            case HttpMethod::GET:
                curl_setopt($ch, CURLOPT_URL, $url."?".http_build_query($parameters));
                break;
            case HttpMethod::PUT:
                curl_setopt($ch, CURLOPT_PUT, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
                break;
            case HttpMethod::DELETE:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
                break;
            default:
                throw new ClientException("Unrecognized http method!");
                break;
        }
        if ($this->userAgent) {
            curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        try {
            $server_output = curl_exec($ch);
            if ($server_output===false) {
                throw new ClientException(curl_error($ch));
            }
            $this->responseWrapper->wrap($server_output);
        } finally {
            curl_close($ch);
        }
    }
}
