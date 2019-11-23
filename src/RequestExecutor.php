<?php
namespace Lucinda\OAuth2;


/**
 * Defines request execution methods.
 */
interface RequestExecutor
{
    /**
     * Executes request
     *
     * @param string $url OAuth2 server endpoint url.
     * @param array $parameters Associative array of parameters to send.
     */
    public function execute(string $url, array $parameters): void;
}
