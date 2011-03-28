<?php

class Spaz_Example
{
    const CACHE_LIMIT = 900;

    protected $curl;
    protected $url;
    protected $url_hash;
    protected $url_key;

    protected $server;

    public function __construct($url)
    {
        $this->server = new Memcache();
        $this->server->addServer('localhost');

        $this->url = $url;
        $this->url_hash = md5($url);
        $this->url_key = 'urlinfo_' . $this->url_hash;

        $this->curl = curl_init();
    }

    public function get()
    {
        $res = $this->server->get($this->url_key);
        if ($res === false) {
            curl_setopt($this->curl, CURLOPT_URL, $this->url);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curl, CURLOPT_HEADER, true);
            curl_setopt($this->curl, CURLOPT_FILETIME, true);
            curl_setopt($this->curl, CURLOPT_NOBODY, true);
            curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($this->curl, CURLOPT_MAXREDIRS, 6);
            $headers = curl_exec($this->curl);
            $url_info = curl_getinfo($this->curl);
            // $url_info['headers'] = $headers;
            curl_close($this->curl);

            $res['url']                     = $url_info['url'];
            $res['content_type']            = $url_info['content_type'];
            $res['http_code']               = $url_info['http_code'];
            $res['filetime']                = $url_info['filetime'];
            $res['download_content_length'] = 
                $url_info['download_content_length'];

            $res = json_encode($res);
            $this->server->add($this->url_key, $res, MEMCACHE_COMPRESSED, self::CACHE_LIMIT);
        }

        return json_decode($res);
    }
}
