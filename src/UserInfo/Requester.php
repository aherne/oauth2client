<?php

namespace Lucinda\OAuth2\UserInfo;

use Lucinda\OAuth2\ResponseWrapper;
use Lucinda\OAuth2\Server\Information as ServerInformation;

interface Requester
{
    function request(ServerInformation $serverInformation, ResponseWrapper $responseWrapper, string $accessToken): array;
}
