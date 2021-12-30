<?php
namespace Lucinda\OAuth2;

/**
 * Implements an executor that redirects to payload url using GET parameters
 */
class RedirectionExecutor implements RequestExecutor
{
    private string $redirectURL;
    
    /**
     * Executes request
     *
     * @param string $url OAuth2 server endpoint url.
     * @param array $parameters Associative array of parameters to send.
     */
    public function execute(string $url, array $parameters): void
    {
        $this->redirectURL = $url."?".http_build_query($parameters);
    }
    
    /**
     * Gets remote URL to invoke for authorization code retrieval
     *
     * @return string
     */
    public function getRedirectURL(): string
    {
        return $this->redirectURL;
    }
}
