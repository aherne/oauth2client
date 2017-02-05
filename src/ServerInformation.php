<?php
namespace OAuth2;

/**
 * Encapsulates information about OAuth2 server.
 */
class ServerInformation {
	private $authorizationEndpoint;
	private $tokenEndpoint;
	
	/**
	 * Sets information necessary to request authorization codes and access tokens
	 * 
	 * @param string $authorizationEndpoint
	 * @param string $tokenEndpoint
	 */
	public function __construct($authorizationEndpoint, $tokenEndpoint) {
		$this->authorizationEndpoint = $authorizationEndpoint;
		$this->tokenEndpoint = $tokenEndpoint;
	}
	
	/**
	 * Gets URL to authorization code request endpoint.
	 * 
	 * @return string
	 */
	public function getAuthorizationEndpoint() {
		return $this->authorizationEndpoint;
	}
	
	/**
	 * Gets URL to access token request endpoint.
	 * @return string
	 */
	public function getTokenEndpoint() {
		return $this->tokenEndpoint;
	}
}