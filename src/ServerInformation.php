<?php
namespace OAuth2;

/**
 * Encapsulates information about OAuth2 provider endpoints.
 */
class ServerInformation {
	private $authorizationEndpoint;
	private $tokenEndpoint;
	
	/**
	 * Creates an object
	 * @param string $authorizationEndpoint URL to provider's authorization code service.
	 * @param string $tokenEndpoint URL to provider's access token service.
	 */
	public function __construct($authorizationEndpoint, $tokenEndpoint) {
		$this->authorizationEndpoint = $authorizationEndpoint;
		$this->tokenEndpoint = $tokenEndpoint;
	}
	
	/**
	 * Gets authorization code endpoint.
	 * 
	 * @return string
	 */
	public function getAuthorizationEndpoint() {
		return $this->authorizationEndpoint;
	}
	
	/**
	 * Gets access token endpoint.
	 * 
	 * @return string
	 */
	public function getTokenEndpoint() {
		return $this->tokenEndpoint;
	}
}