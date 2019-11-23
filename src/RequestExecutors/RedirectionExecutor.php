<?php
namespace Lucinda\OAuth2;

/**
 * Implements an executor that redirects to payload url using GET parameters
 */
class RedirectionExecutor implements RequestExecutor
{
    private $redirectURL;
    
    /**
     * {@inheritDoc}
     * @see RequestExecutor::execute()
     */
    public function execute($url, $parameters)
    {
        $this->redirectURL = $url."?".http_build_query($parameters);
    }
    
    /**
     * Gets remote URL to invoke for authorization code retrieval
     *
     * @return string
     */
    public function getRedirectURL()
    {
        return $this->redirectURL;
    }
}
