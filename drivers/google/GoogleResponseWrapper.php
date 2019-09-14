<?php
namespace OAuth2;

/**
 * Implements parsing of Google OAUTH2 API response
 */
class GoogleResponseWrapper extends ResponseWrapper
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
        if (!empty($result["error"])) {
            if (isset($result["error"]["message"])) {
                // error when retrieving resource
                $exception = new ServerException($result["error"]["message"]);
                $exception->setErrorCode($result["error"]["code"]);
                $exception->setErrorDescription($result["error"]["message"]);
                throw $exception;
            } elseif (isset($result["error_description"])) {
                // error when authorization code was already redeemed
                $exception = new ServerException($result["error_description"]);
                $exception->setErrorCode($result["error"]);
                $exception->setErrorDescription($result["error_description"]);
                throw $exception;
            } else {
                // error when authorization code is incorrect
                throw new ServerException($result["error"]);
            }
        }
        $this->response = $result;
    }
}
