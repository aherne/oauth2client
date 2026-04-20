<?php

namespace Lucinda\OAuth2\Vendor\Google\UserInfo;

use Lucinda\OAuth2\UserInfo;
use Lucinda\OAuth2\UserInfo\Extractor as ExtractorInterface;

class Extractor implements ExtractorInterface
{
    public function convert(array $data): UserInfo
    {
        return new UserInfo($data["id"], $data["name"], $data["email"] ?? "");
    }
}