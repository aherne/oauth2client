<?php

namespace Lucinda\OAuth2\Vendor\GitHub\UserInfo;

use Lucinda\OAuth2\UserInfo;
use Lucinda\OAuth2\UserInfo\Extractor as ExtractorInterface;

class Extractor implements ExtractorInterface
{
    public function convert(array $data): UserInfo
    {
        return new UserInfo($data[0]["id"], $data[0]["name"], $data[1]["email"] ?? "");
    }
}