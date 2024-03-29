<?php

namespace Lucinda\OAuth2\Vendor\LinkedIn;

use Lucinda\OAuth2\Server\Exception as ServerException;

/**
 * Implements parsing of LinkedIn OAUTH2 API response
 */
class ResponseWrapper extends \Lucinda\OAuth2\ResponseWrapper
{
    /**
     * Parses response received from OAuth2 server.
     *
     * @param  string $response
     * @throws ServerException
     */
    public function wrap(string $response): void
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
