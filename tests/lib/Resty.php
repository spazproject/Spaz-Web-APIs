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
	
	function __construct()
	{
		$this->http = new HttpRequest();
	}
	
	/**
	 * undocumented function
	 *
	 * @param int $method 
	 * @param mixed $data 
	 * @param array $headers 
	 * @return array
	 * @author Ed Finkler
	 */
	public function get($url, $querydata=null, $headers=null, $options=null) {
		
		$this->http->setMethod(HttpRequest::METH_GET);
		
		$this->http->setUrl($url);
		
		if (isset($querydata)) {
			$this->http->setQueryData($querydata);
		}
		
		if (isset($headers)) {
			$this->http->setHeaders($headers);
		}
		
		if (isset($options)) {
			$this->http->setOptions($options);
		}
		
		$this->http->send();
		
		$resp = $this->http->getResponseData();
		
		return $resp;
	}
	
	
}
