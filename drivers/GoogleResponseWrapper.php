<?php
/**
 * Implements parsing of Google OAUTH2 API response
 */
class GoogleResponseWrapper extends OAuth2\ResponseWrapper {
	public function wrap($response) {
		$result = json_decode($response,true);
		if(json_last_error() != JSON_ERROR_NONE) {
			throw new OAuth2\ServerException(json_last_error_msg());
		}
		if(!empty($result["error"])) {
			$message = "";
			if(isset($result["error"]["message"])) {
				$message = $result["error"]["message"];
			} else if(isset($result["error_description"])) {
				$message = $result["error"]["error_description"];
			} else {
				$message = is_string($result["error"])?$result["error"]:serialize($result["error"]);
			}
			throw new Oauth2\ServerException($message);
		}
		$this->response = $result;
	}
}