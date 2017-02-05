<?php
namespace OAuth2;

class ServerInformation {
	private $authorizationEndpoint;
	private $tokenEndpoint;
	private $resourcesEndpoint;
	
	public function __construct($authorizationEndpoint, $tokenEndpoint, $resourcesEndpoint) {
		$this->authorizationEndpoint = $authorizationEndpoint;
		$this->tokenEndpoint = $tokenEndpoint;
		$this->resourcesEndpoint = $resourcesEndpoint;
	}
	
	public function getAuthorizationEndpoint() {
		return $this->authorizationEndpoint;
	}
	
	public function getTokenEndpoint() {
		return $this->tokenEndpoint;
	}
	
	public function getResourcesEndpoint() {
		return $this->resourcesEndpoint;
	}
}