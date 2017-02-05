<?php
namespace OAuth2;

class Driver {
	private $clientInformation;
	private $serverInformation;

	public function __construct(ClientInformation $clientInformation, ServerInformation $serverInformation) {
		$this->clientInformation = $clientInformation;
		$this->serverInformation = $serverInformation;
	}

	public function getAuthorizationCode($scopes) {
		$acr = new AuthorizationCodeRequest($this->serverInformation->getAuthorizationEndpoint());
		$acr->setClientInformation($this->clientInformation);
		$acr->setRedirectURL($this->clientInformation->getSiteURL());
		$acr->setScope(implode(" ",$scopes));
		$acr->execute(new RedirectionExecutor());
	}

	public function getAccessToken($authorizationCode, ResponseWrapper $resposeWrapper) {
		$atr = new AccessTokenRequest($this->serverInformation->getTokenEndpoint());
		$atr->setClientInformation($this->clientInformation);
		$atr->setCode($authorizationCode);
		$atr->setRedirectURL($this->clientInformation->getSiteURL());
		$atr->execute(new WrappedExecutor($resposeWrapper));
		return new AccessTokenResponse($resposeWrapper->getResponse());
	}

	public function getResource($accessToken, $resourceURL, $fields=array(), ResponseWrapper $resposeWrapper) {
		$we = new WrappedExecutor($resposeWrapper);
		$we->setHttpMethod(HttpMethod::GET);
		$we->addAuthorizationToken("Bearer",$accessToken);
		$parameters = (!empty($fields)?array("fields"=>implode(",",$fields)):array());
		$we->execute($resourceURL, $parameters);
		return $resposeWrapper->getResponse();
	}
}