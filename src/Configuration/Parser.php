<?php

namespace Lucinda\OAuth2\Configuration;

use Lucinda\OAuth2\Client\Exception as ClientException;

/**
 * Detects oauth2 information based on contents of <oauth2> XML tag
 */
class Parser
{
    private \SimpleXMLElement $xml;
    /**
     * @var TagInfo[]
     */
    private array $drivers = [];

    /**
     * Kick-starts detection process.
     *
     * @param  \SimpleXMLElement $xml
     * @throws ClientException If XML is improperly configured.
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        // set drivers
        $this->xml = $xml->oauth2;
        $this->setDrivers();
    }

    /**
     * Sets oauth2 drivers information based on XML
     *
     * @throws ClientException If XML is improperly configured.
     */
    private function setDrivers(): void
    {
        $list = $this->xml->xpath("driver");
        if (empty($list)) {
            throw new ClientException("No driver tag was found!");
        }
        foreach ($list as $element) {
            $information = new TagInfo($element);
            $this->drivers[] = $information;
        }
    }

    /**
     * Gets oauth2 drivers detected
     *
     * @return TagInfo[]
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }
}
