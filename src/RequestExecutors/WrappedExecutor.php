<?php
namespace OAuth2;

require_once("HttpMethod.php");

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
     * @param integer $httpMethod
     */
    public function setHttpMethod($httpMethod = HttpMethod::POST)
    {
        $this->httpMethod = $httpMethod;
    }
    
    /**
     * Sets request user agent
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }
    
    /**
     * Adds header to request
     *
     * @param string $name
     * @param string $value
    */
    public function addHeader($name, $value)
    {
        $this->headers[] = $name.": ".$value;
    }
    
    /**
     * {@inheritDoc}
     * @see RequestExecutor::execute()
     */
    public function execute($endpointURL, $parameters)
    {
        $ch = curl_init();
        switch ($this->httpMethod) {
            case HttpMethod::POST:
                curl_setopt($ch, CURLOPT_URL, $endpointURL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
                break;
            case HttpMethod::GET:
                curl_setopt($ch, CURLOPT_URL, $endpointURL."?".http_build_query($parameters));
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
