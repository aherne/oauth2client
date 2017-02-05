<?php
namespace OAuth2;

class ServerInformation {
	private $authorizationEndpoint;
	private $tokenEndpoint;
	
	public function __construct($authorizationEndpoint, $tokenEndpoint) {
		$this->authorizationEndpoint = $authorizationEndpoint;
		$this->tokenEndpoint = $tokenEndpoint;
	}
	
	public function getAuthorizationEndpoint() {
		return $this->authorizationEndpoint;
	}
	
	public function getTokenEndpoint() {
		return $this->tokenEndpoint;
	}
}