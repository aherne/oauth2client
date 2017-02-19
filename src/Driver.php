<?php
namespace OAuth2;

<<<<<<< HEAD
require_once("ClientInformation.php");
require_once("ServerInformation.php");
require_once("ClientException.php");
require_once("ServerException.php"); 
require_once("Request.php");
require_once("AuthorizationCodeRequest.php");
require_once("AuthorizationCodeResponse.php");
require_once("AccessTokenRequest.php");
require_once("AccessTokenResponse.php");
require_once("RequestExecutor.php");
require_once("RequestExecutors/RedirectionExecutor.php");
require_once("RequestExecutors/WrappedExecutor.php");
// TODO: add refresh token support

/**
 * Encapsulates operations one can perform on an OAuth2 provider. Acts like a single entry point that hides part of OAuth2 providers complexity. 
 * For each provider, you will have to implement a class that extends Driver and implements abstract protected functions.
 */
abstract class Driver {
=======
/**
 * Encapsulates operations to perform on an OAuth2 vendor.
 */
class Driver {
>>>>>>> 56a6116e3779c93d817c9f77b5df6e86784eb702
	private $clientInformation;
	private $serverInformation;

	/**
<<<<<<< HEAD
	 * Creates an object
	 * 
	 * @param ClientInformation $clientInformation Encapsulates
	 * @param ServerInformation $serverInformation
	 */
	public function __construct(ClientInformation $clientInformation) {
=======
	 * Creates a driver instance. 
	 * 
	 * @param ClientInformation $clientInformation
	 * @param ServerInformation $serverInformation
	 */
	public function __construct(ClientInformation $clientInformation, ServerInformation $serverInformation) {
>>>>>>> 56a6116e3779c93d817c9f77b5df6e86784eb702
		$this->clientInformation = $clientInformation;
		$this->serverInformation = $this->getServerInformation();
	}

	/**
<<<<<<< HEAD
	 * Gets authorization code endpoint URL.
	 * 
	 * @param string[] $scopes Scopes to use in access request.
	 * @param string $state Any client state that needs to be passed on to the client request URI.
	 * @return string Full authorization code endpoint URL.
	 * @throws ClientException When client fails to provide mandatory parameters.
	 */
	public function getAuthorizationCodeEndpoint($scopes, $state="") {
		$executor = new RedirectionExecutor();
=======
	 * Calls OAuth2 vendor to create an authorization code and redirect it to ClientInformation.getSiteURL()
	 * 
	 * @param array $scopes List of scopes to request grant to.
	 */
	public function getAuthorizationCode($scopes) {
>>>>>>> 56a6116e3779c93d817c9f77b5df6e86784eb702
		$acr = new AuthorizationCodeRequest($this->serverInformation->getAuthorizationEndpoint());
		$acr->setClientInformation($this->clientInformation);
		$acr->setRedirectURL($this->clientInformation->getSiteURL());
		$acr->setScope(implode(" ",$scopes));
<<<<<<< HEAD
		if($state) $acr->setState($state);
		$acr->execute($executor);
		return $executor->getRedirectURL();
	}

	/**
	 * Gets access token necessary to retrieve resources with.
	 * 
	 * @param string $authorizationCode Authorization code received.
	 * @return AccessTokenResponse Access token response.
	 * @throws ClientException When client fails to provide mandatory parameters.
	 * @throws ServerException When server responds with an error.
	 */
	public function getAccessToken($authorizationCode) {
		$responseWrapper = $this->getResponseWrapper();
=======
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
>>>>>>> 56a6116e3779c93d817c9f77b5df6e86784eb702
		$atr = new AccessTokenRequest($this->serverInformation->getTokenEndpoint());
		$atr->setClientInformation($this->clientInformation);
		$atr->setCode($authorizationCode);
		$atr->setRedirectURL($this->clientInformation->getSiteURL());
<<<<<<< HEAD
		$atr->execute(new WrappedExecutor($responseWrapper));
		return new AccessTokenResponse($responseWrapper->getResponse());
	}

	/**
	 * Gets remote resource based on access token
	 * 
	 * @param string $accessToken OAuth2 access token
	 * @param string $resourceURL URL of remote resource`
	 * @param string[] $fields Fields to retrieve from remote resource.
	 * @return mixed
	 * @throws ClientException When client fails to provide mandatory parameters.
	 * @throws ServerException When server responds with an error.
	 */
	public function getResource($accessToken, $resourceURL, $fields=array()) {
		$responseWrapper = $this->getResponseWrapper();
		$we = new WrappedExecutor($responseWrapper);
=======
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
>>>>>>> 56a6116e3779c93d817c9f77b5df6e86784eb702
		$we->setHttpMethod(HttpMethod::GET);
		$we->addAuthorizationToken("Bearer",$accessToken);
		$parameters = (!empty($fields)?array("fields"=>implode(",",$fields)):array());
		$we->execute($resourceURL, $parameters);
<<<<<<< HEAD
		return $responseWrapper->getResponse();
=======
		return $resposeWrapper->getResponse();
>>>>>>> 56a6116e3779c93d817c9f77b5df6e86784eb702
	}
	
	/**
	 * Gets OAuth2 server information.
	 * 
	 * @return ServerInformation
	 */
	abstract protected function getServerInformation();
	
	/**
	 * Gets OAuth2 server response parser.
	 * 
	 * @return ResponseWrapper
	 */
	abstract protected function getResponseWrapper():
}