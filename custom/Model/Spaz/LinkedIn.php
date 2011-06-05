<?php

class SpazLinkedIn implements SpazMultiPost_Interface
{
    const VISIBLE_ANYONE      = 'anyone';
    const VISIBLE_CONNECTIONS = 'connections-only';

    protected $curl;
    protected $apiUrl = 'http://api.linkedin.com/v1/people/~/shares';

    public function __construct()
    {
		$this->curl = curl_init();
    }

    /**
     *
     * Fields for the XML Body
     *
     * Node                 Parent Node         Required? 	Value                           Notes
     * share                —                   Yes         Child nodes of share            Parent node for all share content
     * comment              share               Conditional	Text of member's comment. (Similar to deprecated current-status field.)	Post must contain comment and/or (content/title and content/submitted-url). Max length is 700 characters.
     * content              share               Conditional	Parent node for information on shared document
     * title                share/content       Conditional	Title of shared document        Post must contain comment and/or (content/title and content/submitted-url). Max length is 200 characters.
     * submitted-url        share/content       Conditional	URL for shared content          Post must contain comment and/or (content/title and content/submitted-url).
     * submitted-image-url	share/content       Optional	URL for image of shared content	Invalid without (content/title and content/submitted-url).
     * description          share/content       Option      Description of shared content	Max length of 400 characters.
     *
  <content>
     <title>Survey: Social networks top hiring tool - San Francisco Business Times</title>
     <submitted-url>http://sanfrancisco.bizjournals.com/sanfrancisco/stories/2010/06/28/daily34.html</submitted-url>
     <submitted-image-url>http://images.bizjournals.com/travel/cityscapes/thumbs/sm_sanfrancisco.jpg</submitted-image-url>
  </content>
     *
     * @param string $message
     * @param array  $metaData
     * @param array  $authData
     * @return string
     *
     */
    public function send($message, $metaData, $authData)
    {
        $postData = array(
            'comment' => $message,
            'content' => array(
                'title' => $metaData['title'],
            ),
            'visibility' => array(
                'code' => ($metaData['visibility']) ? self::VISIBLE_ANYONE : self::VISIBLE_CONNECTIONS,
            ),
        );
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