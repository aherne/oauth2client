<?php
namespace Lucinda\OAuth2\Configuration;

use Lucinda\OAuth2\Client\Exception as ClientException;

/**
 * Detects oauth2 information based on contents of <oauth2> XML tag
 */
class Parser
{
    private $xml;
    private $drivers = array();
    
    /**
     * Kick-starts detection process.
     *
     * @param \SimpleXMLElement $xml
     * @param string $developmentEnvironment
     * @throws ClientException If XML is improperly configured.
     */
    public function __construct(\SimpleXMLElement $xml, $developmentEnvironment)
    {
        // set drivers
        $this->xml = $xml->oauth2->{$developmentEnvironment};
        if (!$this->xml) {
            throw new ClientException("Missing 'driver' subtag of '".$developmentEnvironment."', child of 'oauth2' tag");
        }
        $this->setDrivers();
    }
    
    /**
     * Sets oauth2 drivers information based on XML
     *
     * @throws ClientException If XML is improperly configured.
     */
    private function setDrivers()
    {
        $xmlLocal = (array) $this->xml;
        if (empty($xmlLocal["driver"])) {
            return;
        }
        $list = (is_array($xmlLocal["driver"])?$xmlLocal["driver"]:[$xmlLocal["driver"]]);
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
    public function getDrivers()
    {
        return $this->drivers;
    }
}