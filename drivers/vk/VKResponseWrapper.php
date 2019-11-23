<?php
namespace Lucinda\OAuth2;

/**
 * Implements parsing of VK OAUTH2 API response
 */
class VKResponseWrapper extends ResponseWrapper
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