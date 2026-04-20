<?php

namespace Lucinda\OAuth2\Vendor\Microsoft;

use Lucinda\OAuth2\ResponseWrapper;
use Lucinda\OAuth2\Vendor\Microsoft\ResponseWrapper as VendorResponseWrapper;
use Lucinda\OAuth2\Vendor\Microsoft\UserInfo\Extractor as VendorExtractor;
use Lucinda\OAuth2\Vendor\Microsoft\UserInfo\Requester as VendorRequester;
use Lucinda\OAuth2\UserInfo\Requester as UserInfoRequester;
use Lucinda\OAuth2\UserInfo\Extractor as UserInfoExtractor;

/**
 * Implements Facebook OAuth2 driver on top of \Lucinda\OAuth2\Driver architecture
 */
class Driver extends \Lucinda\OAuth2\Driver
{
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
