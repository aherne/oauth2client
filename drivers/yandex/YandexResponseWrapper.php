<?php
namespace Lucinda\OAuth2;

/**
 * Implements parsing of Yandex OAUTH2 API response
 */
class YandexResponseWrapper extends ResponseWrapper
{
    /**
     * Parses response received from OAuth2 server.
     *
     * @param string $response
     */
    public function wrap($response)
    {
        $result = json_decode($response, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ServerException(json_last_error_msg());
        }
        if (!empty($result["error"])) {
            $exception = new ServerException($result["error_description"]);
            $exception->setErrorCode($result["error"]);
            $exception->setErrorDescription($result["error_description"]);
            throw $exception;
        } elseif (!$response) {
            throw new ServerException("OAuth2 token is invalid!");
        }
        $this->response = $result;
    }
}
