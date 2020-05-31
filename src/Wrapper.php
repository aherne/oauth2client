<?php
namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Configuration\Parser;
use Lucinda\OAuth2\Configuration\TagInfo;
use Lucinda\OAuth2\Client\Information;
use Lucinda\OAuth2\Vendor\Facebook\Driver as FacebookDriver;
use Lucinda\OAuth2\Vendor\Github\Driver as GitHubDriver;
use Lucinda\OAuth2\Vendor\Google\Driver as GoogleDriver;
use Lucinda\OAuth2\Vendor\Instagram\Driver as InstagramDriver;
use Lucinda\OAuth2\Vendor\LinkedIn\Driver as LinkedInDriver;
use Lucinda\OAuth2\Vendor\VK\Driver as VKDriver;
use Lucinda\OAuth2\Vendor\Yahoo\Driver as YahooDriver;
use Lucinda\OAuth2\Vendor\Yandex\Driver as YandexDriver;
use Lucinda\OAuth2\Client\Exception;

/**
 * Locates and instances oauth2 drivers based on XML content
 */
class Wrapper
{
    private $results = [];
    
    /**
     * Reads XML tag oauth2.{environment}, finds and saves drivers found.
     *
     * @param \SimpleXMLElement $xml
     * @param string $developmentEnvironment
     */
    public function __construct(\SimpleXMLElement $xml, string $developmentEnvironment)
    {
        $parser = new Parser($xml, $developmentEnvironment);
        $tags = $parser->getDrivers();
        foreach ($tags as $tag) {
            $this->setDriver($tag);
        }
    }
    
    /**
     * Converts TagInfo data (originating from a driver tag) into a Driver instance
     *
     * @param TagInfo $tagInfo
     * @throws Exception
     */
    private function setDriver(TagInfo $tagInfo): void
    {
        $clientInformation = new Information($tagInfo->getClientId(), $tagInfo->getClientSecret(), $tagInfo->getCallbackUrl());
        $driverName = $tagInfo->getDriverName();
        $driver = null;
        switch ($driverName) {
            case "Facebook":
                $driver = new FacebookDriver($clientInformation, $tagInfo->getScopes());
                break;
            case "GitHub":
                $driver = new GitHubDriver($clientInformation, $tagInfo->getScopes());
                $driver->setApplicationName($tagInfo->getApplicationName());
                break;
            case "Google":
                $driver = new GoogleDriver($clientInformation, $tagInfo->getScopes());
                break;
            case "Instagram":
                $driver = new InstagramDriver($clientInformation, $tagInfo->getScopes());
                break;
            case "LinkedIn":
                $driver = new LinkedInDriver($clientInformation, $tagInfo->getScopes());
                break;
            case "VK":
                $driver = new VKDriver($clientInformation, $tagInfo->getScopes());
                break;
            case "Yahoo":
                $driver = new YahooDriver($clientInformation, $tagInfo->getScopes());
                break;
            case "Yandex":
                $driver = new YandexDriver($clientInformation, $tagInfo->getScopes());
                break;
            default:
                throw new Exception("Driver not supported: ".$driverName);
                break;
        }
        $this->results[$tagInfo->getCallbackUrl()] = $driver;
    }
    
    /**
     * Gets Driver instances detected based on callback URL
     *
     * @param string $callbackURL
     * @return Driver|NULL|Driver[string]
     */
    public function getDriver(string $callbackURL = "")
    {
        if ($callbackURL) {
            return (isset($this->results[$callbackURL])?$this->results[$callbackURL]:null);
        } else {
            return $this->results;
        }
    }
}
