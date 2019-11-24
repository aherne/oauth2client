<?php
namespace Lucinda\OAuth2\Vendor\Yahoo;

use \Lucinda\OAuth2\Server\Exception as ServerException;

/**
 * Implements parsing of Yahoo OAUTH2 API response
 */
class ResponseWrapper extends \Lucinda\OAuth2\ResponseWrapper
{
    /**
     * Parses response received from OAuth2 server.
     *
     * @param string $response
     */
    public function wrap(string $response): void
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
        } elseif (isset($result["error"]["description"])) {
            throw new ServerException($result["error"]["description"]);
        }
        $this->response = $result;
    }
}
