<?php

class Thumbnails_Model extends MyThumbnailsDb
{
    public function add($name, $url)
    {
        $docId = uniqid();
        $data = array('url' => $url, 'image' => $name);

        $this->db->put($docId, $data);
    }

    public function getByUrl($url, $limit = 1)
    {
        $url = urlencode($url);

        $view_result = $this->db->view(
            'thumbs', 'getByUrl', 'get', null, "key=\"$url\"&limit=$limit"
        );

        $data = $view_result->getBody();
        
        $info = json_decode($data); 

        if (isset($info->rows) && isset($info->rows[0])) {
            return $info->rows[0]->value;
        }
        
        return false;
    }
}
