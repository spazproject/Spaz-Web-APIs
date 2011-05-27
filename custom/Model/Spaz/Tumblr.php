<?php

class SpazTumblr implements SpazMultiPost_Interface
{
    const POST_TYPE_REGULAR = 'regular';
    const FORMAT_MARKDOWN   = 'markdown';
    const FORMAT_HTML       = 'html';

    protected $curl;
    protected $apiUrl = 'http://www.tumblr.com/api/write';

    public function __construct()
    {
		$this->curl = curl_init();
    }

    public function send($message, $metaData, $authData)
    {
        $postData = array(
            'email' => $authData['email'],
            'password' => $authData['password'],
            'type' => self::POST_TYPE_REGULAR,
            'title' => $metaData['title'],
            'body' => $message,
            'format' => self::FORMAT_MARKDOWN,
        );
        $curlOpts = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_URL => $this->apiUrl,
        );
        curl_setopt_array($this->curl, $curlOpts);
        $data = curl_exec($this->curl);
        curl_close($this->curl);
        return $data;
    }

}