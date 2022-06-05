<?php

namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Client\Exception as ClientException;
use Lucinda\OAuth2\Server\Exception as ServerException;
use Lucinda\URL\Request as UrlRequest;
use Lucinda\URL\FileNotFoundException;
use Lucinda\URL\Request\Method;

/**
 * Implements an executor on top of cURL implementing OAuth2 request execution rules in accordance to RFC6749.
 */
class WrappedExecutor implements RequestExecutor
{
    protected ResponseWrapper $responseWrapper;
    protected Method $httpMethod;
    /**
     * @var array<string,string>
     */
    protected array $headers = [];
    protected string $userAgent = "";

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
     * @param Method $httpMethod
     */
    public function setHttpMethod(Method $httpMethod = Method::POST): void
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
        $this->headers[$name] = $value;
    }

    /**
     * Executes request
     *
     * @param  string              $url        OAuth2 server endpoint url.
     * @param  array<string,mixed> $parameters Associative array of parameters to send.
     * @throws ClientException
     * @throws ServerException
     * @throws FileNotFoundException
     */
    public function execute(string $url, array $parameters): void
    {
        try {
            $request = new UrlRequest();
            $request->setMethod($this->httpMethod);
            if ($this->httpMethod==Method::POST) {
                $request->setParameters($parameters);
                $request->setURL($url);
            } else {
                $request->setURL($url.($parameters ? "?".http_build_query($parameters) : ""));
            }
            $headers = $request->setHeaders();
            if ($this->userAgent) {
                $headers->setUserAgent($this->userAgent);
            }
            foreach ($this->headers as $name=>$value) {
                $headers->addCustomHeader($name, $value);
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
