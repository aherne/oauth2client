<?php
/**
 * Implements custom request to retrieve GitHub resource.
 */
class GithubResourceExecutor implements OAuth2\RequestExecutor {
	protected $responseWrapper;
	
	public function __construct(OAuth2\ResponseWrapper $responseWrapper) {
		$this->responseWrapper = $responseWrapper;
	}
	
	/**
	 * Sets OAuth2 access token received via authorization code.
	 * 
	 * @param string $accessToken
	 */
	public function setAuthorizationToken($accessToken) {
		$this->headers[] = "Authorization: token ".$accessToken;
	}
	
	/**
	 * Sets GitHub application name.
	 * 
	 * @param unknown $applicationName
	 */
	public function setApplicationName($applicationName) {
		$this->applicationName = $applicationName;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \OAuth2\RequestExecutor::execute()
	 */
	public function execute($endpointURL, $parameters) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$endpointURL."?".http_build_query($parameters));
		curl_setopt($ch, CURLOPT_USERAGENT, $this->applicationName);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		try {
			$server_output = curl_exec ($ch);
			if($server_output===false) {
				throw new OAuth2\ClientException(curl_error($ch));
			}
			$this->responseWrapper->wrap($server_output);
		} finally {
			curl_close ($ch);
		}
	}
}