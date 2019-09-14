<?php
namespace OAuth2;

/**
 * Implements parsing of Yahoo OAUTH2 API response
 */
class YahooResponseWrapper extends ResponseWrapper
{
    /**
     * {@inheritDoc}
     * @see ResponseWrapper::wrap()
     */
    public function wrap($response)
    {
        $result = json_decode($response, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ServerException(json_last_error_msg());
        }
        if (!empty($result["error_description"])) {
            $exception = new ServerException($result["error_description"]);
            $exception->setErrorCode($result["error"]);
            $exception->setErrorDescription($result["error_description"]);
            throw $exception;
        } else if(isset($result["error"]["description"])) {
            throw new ServerException($result["error"]["description"]);
        }
        $this->response = $result;
    }
}
