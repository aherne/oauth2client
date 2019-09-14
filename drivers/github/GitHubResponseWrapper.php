<?php
namespace OAuth2;

/**
 * Implements parsing of GitHub OAUTH2 API response
 */
class GitHubResponseWrapper extends ResponseWrapper
{
    /**
     * {@inheritDoc}
     * @see ResponseWrapper::wrap()
     */
    public function wrap($response)
    {
        $result = json_decode($response, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            parse_str($response, $result);
            if (!empty($result["error"])) {
                // error when authorization code is invalid
                parse_str($response, $result);
                $exception = new ServerException($result["error"]);
                $exception->setErrorCode($result["error"]);
                $exception->setErrorDescription($result["error_description"]);
                $exception->setErrorURL($result["error_uri"]);
                throw $exception;
            } elseif (!empty($result["message"])) {
                // error when access token is invalid or in retrieving resource
                throw new ServerException($result["message"]);
            }
        }
        $this->response = $result;
    }
}
