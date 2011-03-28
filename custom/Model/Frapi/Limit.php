<?php

class Frapi_Limit
{
    protected $server;

    public function __construct()
    {
        $this->server = new Memcache();
        $this->server->addServer('localhost');
    }

    public function hasAccess($ip)
    {
        $has = $this->getLatestByIp($ip);
        if ($has) {
            return false;
        }

        return true;
    }

    public function addAccess($ip)
    {
        return $this->newEntry($ip);
    }

    public function getLatestByIp($ip)
    {
        return $this->server->get($ip);
    }

    public function newEntry($ip)
    {
        $this->server->add($ip, 1, MEMCACHE_COMPRESSED, 60);
    }
}
