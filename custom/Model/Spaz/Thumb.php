<?php

class Spaz_Thumb
{
    const API_KEY = 'thisisthegoodkeyyoushoudlputhere';

    const THUMB_SIZE = 'medium2';
    const BASE_URL = 'http://api.getspaz.com';

    /**
     * The webthumb object container
     *
     * @var Bluga_Webthumb $this->webthumb   The bluga object
     */
    protected $webthumb;

    /**
     * The bluga webthumb job
     */
    protected $job;

    /**
     * Constructor
     *
     * This is the constructor for the Spaz_Thumb object. By passing
     * the URL to the constructor we add the url and submit a job on
     * the webthumb server.
     *
     * @param string $url  The URL to get the thumb about
     */
    public function __construct($url)
    {
        $this->webthumb = new Bluga_Webthumb();
        $this->webthumb->setApiKey(self::API_KEY);

        $this->job = $this->webthumb->addUrl($url, self::THUMB_SIZE, 1024, 768);
        $this->webthumb->submitRequests();
    }

    /**
     * Get the new url thumbnail
     *
     * This method fetches an image (thumb) to a file.
     *
     * @return integer  The id of the job 
     */
    public function get()
    {
        while (!$this->webthumb->readyToDownload()) {
            sleep (1);
            $this->webthumb->checkJobStatus();
        }

        $this->webthumb->fetchToFile($this->job, null, null, CUSTOM_MODEL . '/images');
        return $this->job->status->id; 
    }
}
