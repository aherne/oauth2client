<?php
namespace OAuth2;

/**
 * Defines response wrapping methods.
 */
abstract class ResponseWrapper {
	private $response;
	
	/**
	 * Parses response received from OAuth2 server.
	 * NOTE: this is Oauth2-client specific.
	 * 
	 * @param string $response
	 */
	abstract public function wrap($response);
	
	/**
	 * Gets parsed response 
	 * 
	 * @return mixed
	 */
	public function getResponse() {
		return $this->response;
	}
}