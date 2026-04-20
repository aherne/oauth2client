<?php

namespace Lucinda\OAuth2\Vendor\LinkedIn\UserInfo;

use Lucinda\OAuth2\UserInfo;
use Lucinda\OAuth2\UserInfo\Extractor as ExtractorInterface;

class Extractor implements ExtractorInterface
{
    public function convert(array $data): UserInfo
    {
        return new UserInfo($data["sub"], $data["name"], $data["email"] ?? "");
    }
}