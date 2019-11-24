<?php
namespace Lucinda\OAuth2\Vendor\VK;

use \Lucinda\OAuth2\Server\Exception as ServerException;

/**
 * Implements parsing of VK OAUTH2 API response
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
        if (!empty($result["error"])) {
            if (isset($result["error"]["error_code"])) {
                // error when retrieving resource
                $exception = new ServerException($result["error"]["error_msg"]);
                $exception->setErrorCode($result["error"]["error_code"]);
                $exception->setErrorDescription($result["error"]["error_msg"]);
                throw $exception;
            } else {
                // error when retrieving access token / when user denies app access
                $exception = new ServerException($result["error_description"]);
                $exception->setErrorCode($result["error"]);
                $exception->setErrorDescription($result["error_description"]);
                throw $exception;
            }
        }
        $this->response = $result;
    }
}
