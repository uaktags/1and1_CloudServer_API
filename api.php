<?php
namespace AIOX;
use \Httpful;

class cloudBuilder {
	private $api_key = "";
	public	$base_url = "https://cloudpanel-api.1and1.com/v1/";
	
	public function __construct($api_key, $base_url = '') {
		$this->api_key = $api_key;
		if($base_url != '')
			$this->base_url = $base_url;
	}

	// Retrieves a list of servers on your account.
	// Returns a multidimensional array containing droplet info on success, or throws an exception on error.
	public function servers() {
		$response = $this->make_get_request("servers");
		return $response['body'];
		
	}
	
	// Retrieves a count of servers on your account.
	// Returns the value.
	public function servers_count() {
		$response = $this->servers();
		if ($response) {
			return count($response['body']);
		} else {
			return false;
		}
	}
	
	// Retrieves information for specified $serverid.
	// Returns an array on success or throws an exception on error or null return.
	public function server_info($serverid) {
		$response = $this->make_get_request("servers/".$serverid."?");
		if($this->was_response_ok($response['code']))
		{
			return $response['body'];
		}else{
			throw new Exception('Non-200 HTTP status code. Check your API key or method');
		}
	}
	
	// Creates a new Droplet.
	// Returns an array of new droplet parameters on success or throws an exception on error.
	public function server_new($features=[]) {
		$response = $this->make_post_request("servers", $features);
		return $response['body'];
	}
		
	public function server_reboot($serverid, $hardware = false) {
		$response = $this->make_get_request("servers/".$serverid."/reboot/?");
		if($this->was_response_ok($response['code'])){
				return $response[1]['event_id'];
		} else {
			throw new Exception('Non-200 HTTP status code. Check your API key or method');
		}
	}
	
	public function server_shutdown($serverid, $hardware = false) {
		$response = $this->make_get_request("servers/".$serverid."/shutdown/?");
		if($this->was_response_ok($response['code'])){
			return $response['body']
		} else {
			throw new Exception('Non-200 HTTP status code. Check your API key or method');
		}
	}
					
	protected function make_get_request($url) {
		$url = $this->base_url . $url;
		$response = \Httpful\Request::get($url)
		  ->sendsJson()
		  ->addHeader("Accept" , "application/json")
		  ->addHeader('X-TOKEN', $this->api_key)
		  ->send();
		return array('code'=>$response->code, 'body'=>$response->body);
	}
	
	protected function make_post_request($url, $details) {
		$url = $this->base_url . $url;
		$response = \Httpful\Request::post($url, $details)
		  ->sendsJson()
		  ->addHeader("Accept" , "application/json")
		  ->addHeader('X-TOKEN', $this->api_key)
		  ->send();
		return $response->body;
	}
	
	protected function was_response_ok($status) {
		switch($status){
			case '200':
				return true;
				break;
			case '201':
				return true;
				break;
			default:
				return false;
				break;
		}
	}

}

?>
