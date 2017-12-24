<?php
/**
 * Implements parsing of LinkedIn OAUTH2 API response
 */
class LinkedinResponseWrapper extends OAuth2\ResponseWrapper {
	/**
	 * {@inheritDoc}
	 * @see \OAuth2\ResponseWrapper::wrap()
	 */
	public function wrap($response) {
		$result = json_decode($response,true);
		if(json_last_error() != JSON_ERROR_NONE) {
			throw new OAuth2\ServerException(json_last_error_msg());
		}
		if(!empty($result["error"])) {
			$exception = new OAuth2\ServerException($result["error"]);
			$exception->setErrorCode($result["error"]);
			$exception->setErrorDescription($result["error_description"]);
			throw $exception;
		}
		$this->response = $result;
	}
}