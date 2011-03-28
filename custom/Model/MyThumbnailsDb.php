<?php
/**
 */
class MyThumbnailsDb
{
    public $db;

    public function __construct()
    {
        $this->db = new CouchDB('thumbnails', 'localhost', '5984');
    }
}
