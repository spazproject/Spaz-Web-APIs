<?php

/**
* 
*/
class Resty
{
	/**
	 * HttpRequest object
	 *
	 * @var HttpRequest
	 */
	protected $http;
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	protected $debug = false;
	
	
	function __construct()
	{
		$this->http = new HttpRequest();
	}
	
	/**
	 * undocumented function
	 *
	 * @param int $method 
	 * @param mixed $querydata 
	 * @param array $headers 
	 * @param array $options 
	 * @return array
	 * @author Ed Finkler
	 */
	public function get($url, $querydata=null, $headers=null, $options=null) {
		return $this->sendRequest($url, HttpRequest::METH_GET, $querydata, $headers, $options);
	}
	
	public function post($url, $querydata=null, $headers=null, $options=null) {		
		return $this->sendRequest($url, HttpRequest::METH_POST, $querydata, $headers, $options);
	}
	
	public function put($url, $querydata=null, $headers=null, $options=null) {		
		return $this->sendRequest($url, HttpRequest::METH_PUT, $querydata, $headers, $options);
	}
	
	public function delete($url, $querydata=null, $headers=null, $options=null) {		
		return $this->sendRequest($url, HttpRequest::METH_DELETE, $querydata, $headers, $options);
	}
	
	
	/**
	 * undocumented function
	 *
	 * @param string $state 
	 * @return void
	 * @author Ed Finkler
	 */
	public function enableDebugging($state=false) {
		$state = (bool)$state;
		
		$this->debug = $state;
	}
	
	
	/**
	 * undocumented function
	 *
	 * @param string $msg 
	 * @return void
	 * @author Ed Finkler
	 */
	protected function log($msg) {
		
		if (!$this->debug) { return; }
		
		echo date(DateTime::RFC822) . " :: ";
		
		if (is_string($msg)) {
			echo "{$msg}\n";
		} else {
			var_dump($msg);
			echo "\n";
		}
	}
	
	
	/**
	 * undocumented function
	 *
	 * @param string $url 
	 * @param string $method 
	 * @param string $querydata 
	 * @param string $headers 
	 * @param string $options 
	 * @return array
	 * @author Ed Finkler
	 */
	public function sendRequest($url, $method=HttpRequest::METH_GET, $querydata=null, $headers=null, $options=null) {
		
		$this->log($url);
		$this->http->setUrl($url);
		
		$this->log($method);
		$this->http->setMethod($method);
		
		$this->log($querydata);
		if (isset($querydata)) {
			$this->http->setQueryData($querydata);
		}
		
		$this->log($headers);
		if (isset($headers)) {
			$this->http->setHeaders($headers);
		}
		
		$this->log($options);
		if (isset($options)) {
			$this->http->setOptions($options);
		}
		
		$this->log("Sending…");
		$this->http->send();
		
		$this->log("Getting response…");
		$resp = $this->http->getResponseData();
		$this->log($resp);
		
		$this->log("Processing response body…");
		$resp = $this->processResponseBody($resp);
		$this->log($resp['body']);
		
		return $resp;
	}
	
	
	/**
	 * undocumented function
	 *
	 * @param string $resp 
	 * @return void
	 * @author Ed Finkler
	 */
	protected function processResponseBody($resp) {
		
		if (strpos($resp['headers']['Content-Type'], 'json') !== FALSE) {
			
			$this->log("Response body is JSON");
			$resp['body'] = json_decode($resp['body']);
			
		} elseif (strpos($resp['headers']['Content-Type'], 'xml') !== FALSE) {
			
			$this->log("Response body is XML");
			$resp['body'] = new SimpleXMLElement($resp['body']);
			
		} else {
			$this->log("Response body wasn't XML or JSON; not parsed");
		}
		
		return $resp;
		
	}
	
}
