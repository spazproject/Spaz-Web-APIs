<?php

/**
 * Implements pass-through posting to LinkedIn API from the Spaz web API.
 *
 * @author Brian Fenton
 */
class SpazLinkedIn implements SpazMultiPost_Interface
{
    /**
     * Visibility settings for comments
     */
    const VISIBLE_ANYONE      = 'anyone';
    const VISIBLE_CONNECTIONS = 'connections-only';

    /**
     * Curl handler. Will eventually replace with pecl_http
     * @var resource
     */
    protected $curl;
    /**
     * API resource URI for posts on LinkedIn
     * @var string
     */
    protected $apiUrl = 'http://api.linkedin.com/v1/people/~/shares';

    public function __construct()
    {
		$this->curl = curl_init();
    }

    /**
     * Adds a new Share to the user's LinkedIn account.
     *
     * Allowed metadata:
     *     visibility (boolean) - Whether share is visible to everyone. Default: true
     *     title (string) - must also include url if this is set
     *     url (string) - URL to share
     *     image_url (string) - URL of image to share along with title/url
     *     description (string) - description of share (optional). Max 400 chars.
     *
     * @param string $message
     * @param array  $metaData
     * @param array  $authData Required OAuth credentials
     * @return string
     *
     */
    public function send($message, $metaData, $authData)
    {
        $postData = array(
            'comment' => $message,
            'visibility' => array(
                'code' => ($metaData['visibility']) ? self::VISIBLE_ANYONE : self::VISIBLE_CONNECTIONS,
            ),
        );
        if (!empty($metaData['title']) && !empty($metaData['url'])) {
            $postData['content'] = array(
                'title' => $metaData['title'],
                'submitted-url' => $metaData['url'],
            );
            if (!empty($metaData['image_url'])) {
                $postData['content']['submitted-image-url'] = $metaData['image_url'];
            }
            if (!empty($metaData['description'])) {
                $postData['content']['description'] = $metaData['description'];
            }
        }
        $curlOpts = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_HTTPHEADER => 'Content-Type: application/json',
        );
        curl_setopt_array($this->curl, $curlOpts);
        $data = curl_exec($this->curl);
        curl_close($this->curl);
        return $data;

        /*$http = new HttpRequest($this->apiUrl, HttpRequest::METH_POST);
        $http->setContentType('application/json');
        $http->setRawPostData(json_encode($postData));
        $http->send();
        return $http->getResponseData();*/
    }

}