<?php


/**
* 
*/
class Spaz_Url
{
	
	const MAX_REDIRECTS = 10;
	
	public $data = array();
	
	protected $curl;
	
	function __construct() {
		$this->cache = Frapi_Cache::getInstance('apc');
		
		$this->curl = curl_init();
	}
	
	
	protected function validate($url) {
		$url = filter_var($url, FILTER_VALIDATE_URL);
		
		return $url;
	}
	
	public function resolve($url)
	{
		$passed_url = $this->validate($url);

		if (!$passed_url) {
			$this->data['error']	 = 'invalid url';
			$this->data['error_message']	 = 'invalid URL passed. Whoops!';
			return $this->data;
		}

		$this->data['passed_url'] = $passed_url;
		
		$result = $this->cache->get("resolved_".$passed_url);
		if (!$result) {
			$this->data = $this->resolve_url($this->data);
			$this->data['cached'] = false;
			$this->cache->add("resolved_".$passed_url, $this->data);
		} else {
			$this->data = $result;
			$this->data['cached'] = true;
		}
		
		return $this->data;
	}
	
	
	protected function resolve_url($data) {
		define('MAX_REDIRECTS', 10);
		define('ONE_HOUR', 1000 * 60);

		if (!isset($data['redirects'])) { $data['redirects'] = 0; }

		try {
			$req = new \HttpRequest($data['passed_url'], \HttpRequest::METH_HEAD);
			$req->setOptions(array('redirect' => self::MAX_REDIRECTS));
			$req->send();

			$resp_code = $req->getResponseCode();

			if ($resp_code >= 400 && $resp_code < 600) {

				$data['error'] = 'http-error';
				$data['error_message'] = $resp_code;
				return $data;

			} elseif ($resp_code >= 200 && $resp_code < 300) {

				$data['final_url'] = $req->getResponseInfo('effective_url');
				$data['redirects'] = $req->getResponseInfo('redirect_count');
				return $data;

			} else {

				$data['error'] = 'Unknown';
				$data['error_message'] = 'Something didn\'t work, bro';
				return $data;

			}

		} catch (HttpException $e) {

			$data['error'] = $e->getCode();
			$data['error_message'] = $e->getMessage();
			return $data;

		}
	}
	
	
	
	/**
	 * Get URL info
	 *
	 * This method fetches info about the URL, like the HTTP response code and content type.
	 *
	 * @return array  Info about the URL
	 */
	public function getInfo($url)
	{
		$url = $this->validate($url);

		if (!$url) {
			$this->data['error']	 = 'invalid url';
			$this->data['error_message']	 = 'invalid URL passed. Whoops!';
			return $this->data;
		}
	
		$res = $this->cache->get("info_".$url);
		if ($res === false) {
			curl_setopt($this->curl, CURLOPT_URL, $url);
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->curl, CURLOPT_HEADER, true);
			curl_setopt($this->curl, CURLOPT_FILETIME, true);
			curl_setopt($this->curl, CURLOPT_NOBODY, true);
			curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($this->curl, CURLOPT_MAXREDIRS, 6);
			curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_2; en-us) AppleWebKit/531.21.8 (KHTML, like Gecko) Version/4.0.4 Safari/531.21.10");
			
			$headers = curl_exec($this->curl);
			$url_info = curl_getinfo($this->curl);
			$url_info['headers'] = $headers;
			curl_close($this->curl);
			
			$res['resolved_url'] = $url_info['url'];
			if (isset($url_info['content_type'])) {
				$res['content_type'] = $url_info['content_type'];
			} else {
				$res['content_type'] = 'unknown';
			}
			
			$res['http_code'] = $url_info['http_code'];
			$res['filetime'] = $url_info['filetime'];
			$res['download_content_length'] = $url_info['download_content_length'];
			
			$this->cache->add("info_".$url, $res);
			$res['cached'] = false;
		} else {
			$res['cached'] = true;
		}
		
		
		return $res;
	}


	/**
	 * Get URL title
	 *
	 * @return array  Info about the URL
	 */
	public function getTitle($url)
	{
		
		$url = $this->validate($url);

		if (!$url) {
			$this->data['error']	 = 'invalid url';
			$this->data['error_message']	 = 'invalid URL passed. Whoops!';
			return $this->data;
		}
	
		
		$res = $this->cache->get("title_".$url);
		if ($res === false) {
			$title = 'Could not retrieve title';
			
			$req = new \HttpRequest($url, \HttpRequest::METH_GET);
			$req->setOptions(array('redirect' => self::MAX_REDIRECTS));
			$req->setHeaders(array(
					'Range' => "bytes=0-1000"
				)
			);
			$req->send();
			
			$html = $req->getResponseBody();
			$status = $req->getResponseCode();	
			$type = $req->getResponseHeader('content-type');
			
			if ($status >= 200 && $status < 300 && $html && $type) {
				if (preg_match("|<title>([^<]+)</title>|i", $html, $matches)) {
					$title = $matches[1];
				}
			}

			$res = array('title'=>$title, 'cached'=>false);
			$this->cache->add("title_".$url, $res);
		} else {
			$res['cached'] = true;
		}

		
		
		return $res;
	}
}

?>