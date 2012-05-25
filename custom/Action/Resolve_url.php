<?php

/**
 * Action Resolve_url 
 * 
 * Array
 * 
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /url/resolve
 */
class Action_Resolve_url extends Frapi_Action implements Frapi_Action_Interface
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

	/**
	 * Default Call Method
	 * 
	 * This method is called when no specific request handler has been found
	 * 
	 * @return array
	 */
	public function executeAction()
	{
		$valid = $this->hasRequiredParameters($this->requiredParams);
		if ($valid instanceof Frapi_Error) {
			return $valid;
		}
		
		return $this->toArray();
	}

	/**
	 * Get Request Handler
	 * 
	 * This method is called when a request is a GET
	 * 
	 * @return array
	 */
	public function executeGet()
	{
		$valid = $this->hasRequiredParameters($this->requiredParams);
		
		if ($valid instanceof Frapi_Error) {
			return $valid;
		}
		
		try {
			$url = $this->getParam('url', self::TYPE_STRING);
			$ur = new Spaz_Url();
			$this->data = $ur->resolve($url);
			return $this->toArray();
		} catch(Exception $e) {
			throw new Frapi_Error($e->getMessage());
		}
		
	}

	/**
	 * Post Request Handler
	 * 
	 * This method is called when a request is a POST
	 * 
	 * @return array
	 */
	public function executePost()
	{
		$valid = $this->hasRequiredParameters($this->requiredParams);
		if ($valid instanceof Frapi_Error) {
			return $valid;
		}
		
		return $this->toArray();
	}

	/**
	 * Put Request Handler
	 * 
	 * This method is called when a request is a PUT
	 * 
	 * @return array
	 */
	public function executePut()
	{
		$valid = $this->hasRequiredParameters($this->requiredParams);
		if ($valid instanceof Frapi_Error) {
			return $valid;
		}
		
		return $this->toArray();
	}

	/**
	 * Delete Request Handler
	 * 
	 * This method is called when a request is a DELETE
	 * 
	 * @return array
	 */
	public function executeDelete()
	{
		$valid = $this->hasRequiredParameters($this->requiredParams);
		if ($valid instanceof Frapi_Error) {
			return $valid;
		}
		
		return $this->toArray();
	}

	/**
	 * Head Request Handler
	 * 
	 * This method is called when a request is a HEAD
	 * 
	 * @return array
	 */
	public function executeHead()
	{
		$valid = $this->hasRequiredParameters($this->requiredParams);
		if ($valid instanceof Frapi_Error) {
			return $valid;
		}
		
		return $this->toArray();
	}





}

