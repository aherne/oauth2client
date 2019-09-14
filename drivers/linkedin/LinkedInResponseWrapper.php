<?php
namespace OAuth2;

/**
 * Implements parsing of LinkedIn OAUTH2 API response
 */
class LinkedInResponseWrapper extends ResponseWrapper
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
            // error when authorization code is invalid
            $exception = new ServerException($result["error_description"]);
            $exception->setErrorCode($result["error"]);
            $exception->setErrorDescription($result["error_description"]);
            throw $exception;
        } elseif (!empty($result["message"])) {
            // error when access token is invalid or in retrieving resource
            throw new ServerException($result["message"]);
        }
        $this->response = $result;
    }
}
