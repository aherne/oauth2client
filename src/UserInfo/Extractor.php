<?php

namespace Lucinda\OAuth2\UserInfo;

use Lucinda\OAuth2\UserInfo;

interface Extractor
{
    function convert(array $data): UserInfo;
}
