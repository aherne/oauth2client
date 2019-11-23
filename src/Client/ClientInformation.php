<?php
namespace Lucinda\OAuth2;

/**
 * Encapsulates information about OAuth2 client.
 */
class ClientInformation
{
    private $appID;
    private $appSecret;
    private $siteURL;
    
    /**
     * Saves information about OAuth2 Client APP
     *
     * @param string $clientID Unique id of client app on OAuth2 vendor
     * @param string $clientSecret Secret key of client app on OAuth2 vendor
     * @param string $siteURL Callback url on client app OAuth2 vendor must redirect authorization codes
     */
    public function __construct(string $clientID, string $clientSecret, string $siteURL): void
    {
        $this->appID = $clientID;
        $this->appSecret = $clientSecret;
        $this->siteURL = $siteURL;
    }
    
    /**
     * Gets unique client/application ID.
     *
     * @return string
     */
    public function getApplicationID(): string
    {
        return $this->appID;
    }

    /**
     * Gets private client secret.
     *
     * @return string
     */
    public function getApplicationSecret(): string
    {
        return $this->appSecret;
    }
    
    /**
     * Gets client default callback URL.
     *
     * @return string
     */
    public function getSiteURL(): string
    {
        return $this->siteURL;
    }
}
