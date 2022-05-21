<?php

namespace Lucinda\OAuth2\Server;

/**
 * Encapsulates error response received from OAuth2 server according to RFC6749
 */
class Exception extends \Exception
{
    private ?string $errorCode = null;
    private ?string $errorDescription = null;
    private ?string $errorURL = null;
    private ?string $state = null;

    /**
     * Sets error code received from server.
     *
     * @param string $errorCode
     */
    public function setErrorCode(string $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * Gets error code received from server.
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Sets error description received from server.
     *
     * @param string $errorDescription
     */
    public function setErrorDescription(string $errorDescription): void
    {
        $this->errorDescription = $errorDescription;
    }

    /**
     * Gets error description received from server.
     *
     * @return string
     */
    public function getErrorDescription(): string
    {
        return $this->errorDescription;
    }

    /**
     * Sets URI of web page with information about the error received from server
     *
     * @param string $errorURL
     */
    public function setErrorURL(string $errorURL): void
    {
        $this->errorURL = $errorURL;
    }

    /**
     * Gets URI of web page with information about the error received from server
     *
     * @return string
     */
    public function getErrorURL(): string
    {
        return $this->errorURL;
    }

    /**
     * Sets opaque value used by the client to maintain state between the request and callback received from server
     *
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * Gets opaque value used by the client to maintain state between the request and callback received from server
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }
}
