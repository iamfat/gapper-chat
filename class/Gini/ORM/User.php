<?php

namespace Gini\ORM;

class User extends RObject
{
    public $name         = 'string:120';
    public $initials     = 'string:10';
    public $username     = 'string:120';
    public $email        = 'string:120';
    public $phone        = 'string:120';
    public $icon         = 'string:250';
    public $admin        = 'int';

    protected static $db_index = [
        'unique:username'
    ];

    public function fetch($force = false)
    {
        if ($force || $this->_db_time == 0) {

            if (is_array($this->_criteria) && count($this->_criteria) > 0) {

                $criteria = $this->normalizeCriteria($this->_criteria);

                $data = null;
                $key = isset($criteria['id']) ? $criteria['id'] :
(isset($criteria['username']) ? $criteria['username'] : null);
                $key = $this->name().'#'.$key;

                $cacher = \Gini\Cache::of('orm');
                $data = $cacher->get($key);

                if (is_array($data)) {
                    \Gini\Logger::of('orm')->debug("cache hits on $key");
                } else {
                    \Gini\Logger::of('orm')->debug("cache missed on $key");
                    $rdata = $this->fetchRPC($criteria);

                    if (is_array($rdata) && count($rdata) > 0) {
                        $data = $this->convertRPCData($rdata);
                        // set ttl to 5 sec
                        $cacher->set($key, $data, $this->cacheTimeout);
                    }
                }

            }

            $this->setData((array) $data);
        }

    }

    protected function fetchRPC($criteria)
    {
        try {
            return (array) self::getRPC()->gapper->user->getInfo($criteria);
        } catch (\Gini\RPC\Exception $e) {}
        return [];
    }

    public function convertRPCData(array $rdata)
    {
        $data = [];
        $data['id'] = $rdata['id'];
        $data['name'] = $rdata['name'];
        $data['initials'] = $rdata['initials'];
        $data['username'] = $rdata['username'];
        $data['email'] = $rdata['email'];
        $data['phone'] = $rdata['phone'];
        $data['icon'] = $rdata['icon'];
        $data['admin'] = $rdata['admin'];
        $data['_extra'] = J(array_diff_key($rdata, array_flip(['id', 'name',
'initials', 'username', 'email', 'phone', 'icon', 'admin'])));

        return $data;
    }

    public function icon($size=null)
    {
        $url = $this->icon;
        if (!$url) return;

        $scheme = parse_url($url)['scheme'];
        if($scheme != 'http') return $url;

        return \Gini\ImageCache::makeURL($url, $size);
    }

}
