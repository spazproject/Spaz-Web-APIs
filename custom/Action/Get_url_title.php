<?php

/**
 * Action Get_url_title 
 * 
 * Retrieves the Title of an HTML doc located at the URL, or the content type if
 * not text/html
 * 
 * @link http://echolibre.com/frapi
 * @author Echolibre <frapi@echolibre.com>
 * @link /url/title
 */
class Action_Get_url_title extends Frapi_Action implements Frapi_Action_Interface
{

	/**
	 * Required parameters
	 * 
	 * @var An array of required parameters.
	 */
	protected $requiredParams = array('url');

	/**
	 * The data container to use in toArray()
	 * 
	 * @var A container of data to fill and return in toArray()
	 */
	private $data = array();
	
	// protected $valid_html_types = array('text/html', 'application/xhtml+xml');

	/**
	 * To Array
	 * 
	 * This method returns the value found in the database 
	 * into an associative array.
	 * 
	 * @return array
	 */
	public function toArray()
	{
		$this->data['url'] = $this->getParam('url', self::TYPE_OUTPUT);
		return $this->data;
	}

	public function executeAction()
	{
		$valid = $this->hasRequiredParameters($this->requiredParams);
		if ($valid instanceof Frapi_Error) {
			return $valid;
		}
		
		return $this->toArray();
	}

	public function executeGet()
	{
		$valid = $this->hasRequiredParameters($this->requiredParams);
		if ($valid instanceof Frapi_Error) {
			return $valid;
		}
		
		/*
			get the url param and decode it
		*/
		$url = $this->getParam('url');
		
		try {
			$model = new Spaz_Url();
			$res = $model->getInfo($url);
		} catch (Exception $e) {
			throw new Frapi_Error($e->getMessage());
		}
		
		
		/*
			is content type html?
		*/
		if (stripos($res['content_type'], 'text/html') !== FALSE
			|| stripos($res['content_type'], 'application/xhtml+xml' !== FALSE)) {
			try {
				if ((int)$res['download_content_length'] < 128*1024) {
					$res = $model->getTitle($url);
				} else {
					$res = array('title'=>'Could not retrieve title');
				}
			} catch (Exception $e) {
				throw new Frapi_Error($e->getMessage());
			}
		} else {
			$title = $res['content_type'];
			$size  = (int)$res['download_content_length'];
			if ($size > 0) {
				$title .= ' ' . $this->formatBytes($size);
			}
			$res = array('title'=>$title);
		}
		
		$this->data = $res;
		
		return $this->toArray();
	}

	public function executePost()
	{
		throw new Frapi_Error('ERROR_GET_EXPECTED');
	}

	public function executePut()
	{
		throw new Frapi_Error('ERROR_GET_EXPECTED');
	}

	public function executeDelete()
	{
		throw new Frapi_Error('ERROR_GET_EXPECTED');
	}



	protected function formatBytes($bytes, $precision = 2) { 
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 

		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 

		$bytes /= pow(1024, $pow); 

		return round($bytes, $precision) . ' ' . $units[$pow]; 
	}

}

