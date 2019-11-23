<?php
namespace Lucinda\OAuth2;

/**
 * Encapsulates a successful authorization code response according to RFC6749
 */
class AuthorizationCodeResponse
{
    protected $code;
    protected $state;

    /**
     * Populates response based on parameter keys defined in RFC6749
     *
     * @param string[string] $parameters Authorization code response parameters received
     */
    public function __construct(array $parameters): void
    {
        $this->code = $parameters["code"];
        if (!empty($parameters["state"])) {
            $this->state = $parameters["state"];
        }
    }
    
    /**
     * Gets authorization code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
    
    /**
     * Gets opaque value used by the client to maintain state between the request and callback
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }
}
