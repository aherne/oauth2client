<?php
require_once("LinkedinResponseWrapper.php");

/**
 * Implements LinkedIn OAuth2 driver.
 */
class LinkedinDriver extends OAuth2\Driver {
	const AUTHORIZATION_ENDPOINT_URL = "https://www.linkedin.com/oauth/v2/authorization";
	const TOKEN_ENDPOINT_URL = "https://www.linkedin.com/oauth/v2/accessToken";
	
	/**
	 * {@inheritDoc}
	 * @see \OAuth2\Driver::getServerInformation()
	 */
	protected function getServerInformation() {
		return new OAuth2\ServerInformation(self::AUTHORIZATION_ENDPOINT_URL, self::TOKEN_ENDPOINT_URL);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \OAuth2\Driver::getResponseWrapper()
	 */
	protected function getResponseWrapper() {
		return new LinkedinResponseWrapper();
	}
	
	/**
	 * {@inheritDoc}
	 * @see \OAuth2\Driver::getAccessToken()
	 */
	public function getAccessToken($authorizationCode) {
		$responseWrapper = $this->getResponseWrapper();
		$we = new OAuth2\WrappedExecutor($responseWrapper);
		$we->setHttpMethod(OAuth2\HttpMethod::POST);
		$we->addHeader("Content-Type", "application/x-www-form-urlencoded");
		$atr = new OAuth2\AccessTokenRequest($this->serverInformation->getTokenEndpoint());
		$atr->setClientInformation($this->clientInformation);
		$atr->setCode($authorizationCode);
		$atr->setRedirectURL($this->clientInformation->getSiteURL());
		$atr->execute($we);
		$response = $responseWrapper->getResponse();
		$response["token_type"] = "Bearer";
		return new OAuth2\AccessTokenResponse($response);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \OAuth2\Driver::getResource()
	 */
	public function getResource($accessToken, $resourceURL, $fields=array()) {
		$responseWrapper = $this->getResponseWrapper();
		$we = new OAuth2\WrappedExecutor($responseWrapper);
		$we->setHttpMethod(OAuth2\HttpMethod::GET);
		$we->addHeader("Authorization", "Bearer ".$accessToken);
		$we->addHeader("x-li-format","json");
		$parameters = (!empty($fields)?array("fields"=>implode(",",$fields)):array());
		$we->execute($resourceURL, $parameters);
		return $responseWrapper->getResponse();
	}
}