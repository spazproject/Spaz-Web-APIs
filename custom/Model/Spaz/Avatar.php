<?php
/**
* 
*/
class SpazAvatar
{
    
    function __construct()
    {
        $this->cache = Frapi_Cache::getInstance('apc');
    }
    
    public function retrieve($service, $userid) {
        
        $ck = "avatar_".$service."_".$userid;
        $url = $this->cache->get($ck);
        
        if (!$url) {
            switch ($service) {
                case 'identi.ca':
                    $url = $this->retrieveIdentica($userid);
                    break;

                case 'twitter.com':
                    $url = $this->retrieveTwitter($userid);
                    break;

                default:
                    # code...
                    break;
            }
            
            $this->cache->add($ck, $url);
            
        }
        
        return $url;
    }
    
    
    protected function retrieveIdentica($userid)
    {
        
        $api_url = "http://identi.ca/api/users/show/{$userid}.json";
        
        $http = new HttpRequest($api_url);
        $http->send();
        
        if ($http->getResponseCode() == 200) {
            $resp_obj = json_decode($http->getResponseBody());
        }
        
        if ($resp_obj && isset($resp_obj->profile_image_url)) {
            $url = $resp_obj->profile_image_url;
            return $url;
        } else {
            return false;
        }
    }


    protected function retrieveTwitter($userid)
    {
        
        $api_url = "http://api.twitter.com/1/users/profile_image/{$userid}.json";
        
        $http = new HttpRequest($api_url, HttpRequest::METH_HEAD);
        $http->send();
        
        $url = $http->getResponseHeader('location');
        if ($url) {
            return $url;
        } else {
            return false;
        }

    }
}
