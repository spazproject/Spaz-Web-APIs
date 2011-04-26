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
class Action_Unreadsync extends Frapi_Action implements Frapi_Action_Interface
{

    /**
     * Required parameters
     *
     * @var An array of required parameters.
     */
    protected $requiredParams = array('timeline', 'replies', 'messages', 'key', 'service', 'userid', 'client');

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
        $this->requiredParams = array('service', 'userid');
        $valid = $this->hasRequiredParameters($this->requiredParams);
        if ($valid instanceof Frapi_Error) {
            return $valid;
        }

        $service = $this->getParam('service', self::TYPE_STRING);
        $userid = $this->getParam('userid', self::TYPE_STRING);

        $sm = new SpazUnreadSync();
        $sync = $sm->retrieve($service, $userid);

        if(!$sync) {
            throw new Frapi_Error('ERROR_RETRIEVING_SYNC');
        }

        return $sync;
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
        $syncParams = array();
        foreach($this->requiredParams as $param) {
         $syncParams[$param] = $this->getParam($param, self::TYPE_STRING);
        }

        $sm = new SpazUnreadSync();
        $sync = $sm->sync($syncParams);

        if (!$sync) {
            throw new Frapi_Error('ERROR_SAVING_SYNC');
        }

        return $this->toArray();
    }

}

