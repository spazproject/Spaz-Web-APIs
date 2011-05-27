<?php

/**
 * Action Unreadsync
 *
 * Sync unread message status
 *
 * @link http://getfrapi.com
 * @author Frapi <frapi@getfrapi.com>
 * @link /sync
 */
class Action_Multipost extends Frapi_Action implements Frapi_Action_Interface
{

    /**
     * Required parameters
     *
     * @var An array of required parameters.
     */
    protected $requiredParams = array('username', 'content', 'services');

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

    /**
     * Default Call Method
     *
     * This method is called when no specific request handler has been found
     *
     * @return array
     */
    public function executeAction()
    {
        throw new Frapi_Error('NOT_IMPLEMENTED');
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
        throw new Frapi_Error('NOT_IMPLEMENTED');
    }

    /**
     * Post Request Handler
     *
     *
     *
     * @return array
     */
    public function executePost()
    {
        $valid = $this->hasRequiredParameters($this->requiredParams);
        if ($valid instanceof Frapi_Error) {
            return $valid;
        }
        $message = $this->getParam('content', self::TYPE_STRING);
        foreach ($this->getParam('services') as $service => $authData) {
            $serviceHandlerClass = 'Spaz' . $service;
            $serviceHandler = new $serviceHandlerClass;
            try {
                $data[] = $serviceHandler->send($message, $authData);
            } catch (Exception $e) {
                throw new Frapi_Error($e->getMessage());
            }
        }

        return $this->toArray();
    }

}

