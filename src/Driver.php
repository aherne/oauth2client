<?php
namespace OAuth2;

/**
 * Encapsulates operations to perform on an OAuth2 vendor.
 */
class Driver {
	private $clientInformation;
	private $serverInformation;

	/**
	 * Creates a driver instance. 
	 * 
	 * @param ClientInformation $clientInformation
	 * @param ServerInformation $serverInformation
	 */
	public function __construct(ClientInformation $clientInformation, ServerInformation $serverInformation) {
		$this->clientInformation = $clientInformation;
		$this->serverInformation = $serverInformation;
	}

	/**
	 * Calls OAuth2 vendor to create an authorization code and redirect it to ClientInformation.getSiteURL()
	 * 
	 * @param array $scopes List of scopes to request grant to.
	 */
	public function getAuthorizationCode($scopes) {
		$acr = new AuthorizationCodeRequest($this->serverInformation->getAuthorizationEndpoint());
		$acr->setClientInformation($this->clientInformation);
		$acr->setRedirectURL($this->clientInformation->getSiteURL());
		$acr->setScope(implode(" ",$scopes));
		$acr->execute(new RedirectionExecutor());
	}

	/**
	 * Calls OAuth2 to create an access token based on authorization code received
	 * 
	 * @param string $authorizationCode Authorization code received from OAuth2 vendor.
	 * @param ResponseWrapper $resposeWrapper Encapsulates logic of reading OAuth2 vendor response.
	 * @return \OAuth2\AccessTokenResponse
	 */
	public function getAccessToken($authorizationCode, ResponseWrapper $resposeWrapper) {
		$atr = new AccessTokenRequest($this->serverInformation->getTokenEndpoint());
		$atr->setClientInformation($this->clientInformation);
		$atr->setCode($authorizationCode);
		$atr->setRedirectURL($this->clientInformation->getSiteURL());
		$atr->execute(new WrappedExecutor($resposeWrapper));
		return new AccessTokenResponse($resposeWrapper->getResponse());
	}

	/**
	 * Gets resource from OAuth2 application based on access token.
	 * 
	 * @param string $accessToken Access token received fromn OAuth2 vendor.
	 * @param string $resourceURL URL to resource needed to get information from.
	 * @param array $fields Fields to retrieve from that resource.
	 * @param ResponseWrapper $resposeWrapper Encapsulates logic of reading OAuth2 vendor response.
	 * @return mixed Returns resource as string or array.
	 */
	public function getResource($accessToken, $resourceURL, $fields=array(), ResponseWrapper $resposeWrapper) {
		$we = new WrappedExecutor($resposeWrapper);
		$we->setHttpMethod(HttpMethod::GET);
		$we->addAuthorizationToken("Bearer",$accessToken);
		$parameters = (!empty($fields)?array("fields"=>implode(",",$fields)):array());
		$we->execute($resourceURL, $parameters);
		return $resposeWrapper->getResponse();
	}
}