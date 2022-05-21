<?php

namespace Lucinda\OAuth2\Configuration;

use Lucinda\OAuth2\Client\Exception as ClientException;

/**
 * Encapsulates information about oauth2 provider detected from a &lt;driver&gt; tag
 */
class TagInfo
{
    private string $driverName;
    private string $clientId;
    private string $clientSecret;
    private string $callbackUrl;
    private ?string $applicationName = null;
    /**
     * @var string[]
     */
    private array $scopes = [];

    /**
     * Starts detection process
     *
     * @param \SimpleXMLElement $element Pointer to tag content
     * @throws ClientException If XML is improperly configured.
     */
    public function __construct(\SimpleXMLElement $element)
    {
        $this->driverName = (string) $element["name"];
        $this->clientId = (string) $element["client_id"];
        $this->clientSecret = (string) $element["client_secret"];
        $this->callbackUrl = (string) $element["callback"];
        if (!$this->driverName || !$this->clientId || !$this->clientSecret || !$this->callbackUrl) {
            throw new ClientException("Attributes are mandatory for 'driver' @ 'oauth2' tag: name, client_id, client_secret, callback");
        }
        if ($this->driverName == "GitHub") {
            $this->applicationName = (string) $element["application_name"];
            if (!$this->applicationName) {
                throw new ClientException("Attribute 'application_name' is mandatory for GitHub 'driver' @ 'oauth2' tag!");
            }
        }
        $this->scopes = explode(",", (string) $element["scopes"]);
    }

    /**
     * Gets name of vendor
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return $this->driverName;
    }

    /**
     * Gets client id to send to vendor in order to obtain an authorization code
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Gets client secret to use in converting authorization code to access token
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * Gets relative url vendor will use to send authorization code
     *
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * Gets application name (requirement when GitHub is used)
     *
     * @return string|NULL
     */
    public function getApplicationName(): ?string
    {
        return $this->applicationName;
    }

    /**
     * Gets scopes to send to vendor in order on authorization code requests
     *
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }
}
