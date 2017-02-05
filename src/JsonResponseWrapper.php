<?php
namespace OAuth2;

class JsonResponseWrapper extends ResponseWrapper {
	public function wrap($response) {
		$result = json_decode($response,true);
		if(json_last_error()!=JSON_ERROR_NONE) {
			throw new ServerException("Response is not JSON: ".$response);
		}
		if(!empty($result["error"])) {
			throw new ServerException($result["error"]["message"]);
		}
		$this->response = $result;
	}
}