<?php

namespace Lucinda\OAuth2\Vendor\Microsoft;

use Lucinda\OAuth2\Server\Exception as ServerException;

/**
 * Implements parsing of Microsoft OAUTH2 API response
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
            $exception = new ServerException($result["error"]);
            $exception->setErrorCode(implode(",", $result["error_codes"]??[]));
            $exception->setErrorDescription($result["error_description"]??"");
            throw $exception;
        }
        $this->response = $result;
    }
}
