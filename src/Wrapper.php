<?php

namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Configuration\Parser;
use Lucinda\OAuth2\Configuration\TagInfo;
use Lucinda\OAuth2\Client\Information as ClientInformation;
use Lucinda\OAuth2\Server\Information as ServerInformation;
use Lucinda\OAuth2\Client\Exception;

/**
 * Locates and instances oauth2 drivers based on XML content
 */
class Wrapper
{
    /**
     * @var array<string,Driver>
     */
    private array $results = [];

    /**
     * Reads XML tag oauth2.{environment}, finds and saves drivers found.
     *
     * @param  \SimpleXMLElement $xml
     * @throws Exception
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $remoteEndpoints = new RemoteEndpoints();

        $parser = new Parser($xml);
        $tags = $parser->getDrivers();
        foreach ($tags as $tag) {
            $this->setDriver($tag, $remoteEndpoints);
        }
    }

    /**
     * Converts TagInfo data (originating from a driver tag) into a Driver instance
     *
     * @param TagInfo $tagInfo
     * @param RemoteEndpoints $remoteEndpoints
     * @throws Exception
     */
    private function setDriver(TagInfo $tagInfo, RemoteEndpoints $remoteEndpoints): void
    {
        $clientInformation = new ClientInformation(
            $tagInfo->getClientId(),
            $tagInfo->getClientSecret(),
            $tagInfo->getCallbackUrl()
        );
        $driverName = $tagInfo->getDriverName();
        $driverClass = "\\Lucinda\\OAuth2\\Vendor\\".$driverName."\\Driver";
        if (!class_exists($driverClass)) {
            throw new Exception("Driver not implemented: ".$driverName);
        }
        if (!$remoteEndpoints->has($driverName)) {
            throw new Exception("Driver not configured: ".$driverName);
        }
        $driver = new $driverClass($clientInformation, $remoteEndpoints->get($driverName));
        if ($driverName == "GitHub") {
            $driver->setApplicationName($tagInfo->getApplicationName());
        }
        $this->results[$tagInfo->getCallbackUrl()] = $driver;
    }

    /**
     * Gets Driver instances detected based on callback URL
     *
     * @param  string $callbackURL
     * @return Driver|null|array<string,Driver>
     */
    public function getDriver(string $callbackURL = ""): Driver|null|array
    {
        if ($callbackURL) {
            return ($this->results[$callbackURL] ?? null);
        } else {
            return $this->results;
        }
    }
}
