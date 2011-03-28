<?php

/**
 * Action Retrieve 
 * 
 * Retrieve information about a certain URL
 * 
 * @link http://echolibre.com/frapi
 * @author Echolibre <frapi@echolibre.com>
 * @link /ur/retrieve
 */
class Action_Retrieve extends Frapi_Action implements Frapi_Action_Interface
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
 
        $url = $this->getParam('url');
        $url = urldecode($url);

        try {
            $model = new Thumbnails_Model();
            $res = $model->getByUrl($url);
        } catch (Exception $e) {
            throw new Frapi_Error($e->getMessage());
        }

        if ($res === false) {
            throw new Frapi_Error('ERROR_NO_SUCH_IMAGE');
        }

        $this->data['url'] = $res->url;
        $this->data['image'] = $res->image;
        $this->data['location'] = '/image/' . $res->image;

        return $this->toArray();
    }
}

