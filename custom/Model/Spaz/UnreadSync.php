<?php

class SpazUnreadSync
{
    function __construct()
    {
        $this->cache = Frapi_Cache::getInstance('apc');
    }

    public function sync($params)
    {
        $ck = "sync_".$params['service']."_".$params['userid'];
        return $this->cache->add($ck, $params);
    }

    public function retrieve($service, $userid)
    {
        $ck = "sync_{$service}_{$userid}";
        $sync = $this->cache->get($ck);
        return $sync;
    }
}