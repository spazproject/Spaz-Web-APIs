<?php

/**
 * Action Get_url_info 
 * 
 * preview the contents of a URL in some way
 * 
 * @link http://echolibre.com/frapi
 * @author Echolibre <frapi@echolibre.com>
 */
class Action_Get_url_info extends Frapi_Action
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


}

