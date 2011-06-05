<?php

class SpazPosterous implements SpazMultiPost_Interface
{

     /**
     * Curl handler. Will eventually replace with pecl_http
     * @var resource
     */
    protected $curl;
    /**
     * API resource URI for uploads on Posterous
     * @var string
     */
    protected $apiUrl = 'https://posterous.com/api2/upload.json';

    public function __construct()
    {
		$this->curl = curl_init();
    }

    /**
     * Adds a new upload to the user's Posterous account.
     *
     * Allowed metadata:
     *     title (string) - title for post/upload
     *     media (array) - one or more paths of files to post (audio, images, video, docs, etc...)
     *
     * @param string $message
     * @param array  $metaData
     * @param array  $authData Required OAuth credentials (passed to Twitter)
     * @return string
     *
     * @todo add mime type guessing
     */
    public function send($message, $metaData, $authData)
    {
        $postData = array(
            'message' => $metaData['title'],
            'body' => $message,
            'source' => 'Spaz',
            'sourceLink' => 'http://www.getspaz.com',
        );
        if (!empty($metaData['media']) && is_array($metaData['media'])) {
            //append @ so curl knows to treat them as files
            foreach ($metaData['media'] as &$path) {
                $path = '@' . $path;
            }
            $postData['media'] = $metaData['media'];
        }
        $headers = array(
            'X-Auth-Service-Provider: https://api.twitter.com/1/account/verify_credentials.json',
            'X-Verify-Credentials-Authorization: ' . $authData, //signed OAuth "Authorization" header typically used to call the verify_credentials.json endpoint',
        );
        $curlOpts = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_HTTPHEADER => $headers,
        );
        curl_setopt_array($this->curl, $curlOpts);
        $data = curl_exec($this->curl);
        curl_close($this->curl);
        return $data;

        /*$headers = array(
            'X-Auth-Service-Provider' => 'https://api.twitter.com/1/account/verify_credentials.json',
            'X-Verify-Credentials-Authorization' => $authData,
        );
        $media = $postData['media'];
        unset($postData['media']);
        $http = new HttpRequest($this->apiUrl, HttpRequest::METH_POST);
        $http->setPostFields($postData);
        $http->setPostFiles($media);
        $http->setHeaders($headers);
        $http->send();
        return $http->getResponseData();*/
    }

}