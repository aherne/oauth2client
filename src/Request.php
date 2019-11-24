<?php
namespace Lucinda\OAuth2;

use Lucinda\OAuth2\Client\Exception as ClientException;

/**
 * Encapsulates a request from OAuth2 client to provider
 */
interface Request
{
    /**
     * Executes request.
     *
     * @param RequestExecutor $executor Performs request execution.
     * @throws ClientException If insufficient parameters are supplied to issue a request.
     */
    public function execute(RequestExecutor $executor): void;
}
