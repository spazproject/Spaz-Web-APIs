<?php

class SpazPosterous implements SpazMultiPost_Interface
{

    protected $curl;
    protected $apiUrl = 'https://posterous.com/api2/upload.json';

    public function __construct()
    {
		$this->curl = curl_init();
    }

    /*
     * The Posterous Twitter API lets you upload photos to a Posterous site.
     * It is used with normal HTTP POST requests. Post data should be formatted
     * as multipart/form-data. This API is a drop-in replacement for the Twitpic API.
     *
     * The API uses Twitter's OAuth Echo method to authenticate the identity of
     * a Twitter user.
     *
     * If the Twitter user is registered on Posterous, it will post to their
     * default Posterous site. If the user is not on Posterous, we will create a
     * new site for them. The media parameter can be an array of media. This
     * includes images, audio, video, and common document formats.
     *
     * Fields
     *     "media" - Optional. File data for single file.
     *     "media[]" - Optional. File data for multiple file upload. Can be specified multiple times.
     *
     * Headers
     * There are two required headers for OAuth Echo:
     *     X-Auth-Service-Provider: https://api.twitter.com/1/account/verify_credentials.json
     *     X-Verify-Credentials-Authorization: Should be the signed OAuth "Authorization" header typically used to call the verify_credentials.json endpoint
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
        $http = new HttpRequest($this->apiUrl, HttpRequest::METH_POST, array('headers' => $headers));
        $http->setPostFields($postData);
        $http->send();
        return $http->getResponseData();*/
    }

}