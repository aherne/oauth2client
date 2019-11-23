<?php
namespace Lucinda\OAuth2;

/**
 * Implements parsing of Instagram OAUTH2 API response
 */
class InstagramResponseWrapper extends ResponseWrapper
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
        if (!empty($result["meta"]["error_message"])) {
            throw new ServerException($result["meta"]["error_message"]);
        }
        $this->response = $result;
    }
}
