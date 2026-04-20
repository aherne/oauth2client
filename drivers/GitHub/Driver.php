<?php

namespace Lucinda\OAuth2\Vendor\GitHub;

use Lucinda\OAuth2\ResponseWrapper;
use Lucinda\OAuth2\Vendor\GitHub\ResponseWrapper as VendorResponseWrapper;
use Lucinda\OAuth2\Vendor\GitHub\UserInfo\Extractor as VendorExtractor;
use Lucinda\OAuth2\Vendor\GitHub\UserInfo\Requester as VendorRequester;
use Lucinda\OAuth2\UserInfo\Requester as UserInfoRequester;
use Lucinda\OAuth2\UserInfo\Extractor as UserInfoExtractor;

/**
 * Implements Facebook OAuth2 driver on top of \Lucinda\OAuth2\Driver architecture
 */
class Driver extends \Lucinda\OAuth2\Driver
{
    private string $applicationName = "";

    public function setApplicationName(string $applicationName): void
    {
        $this->applicationName = $applicationName;
    }

    protected function getResponseWrapper(): ResponseWrapper
    {
        return new VendorResponseWrapper();
    }

    protected function getUserInfoRequester(): UserInfoRequester
    {
        return new VendorRequester();
    }

    protected function getUserInfoExtractor(): UserInfoExtractor
    {
        return new VendorExtractor();
    }
}
