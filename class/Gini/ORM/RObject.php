<?php

namespace Gini\ORM;

abstract class RObject extends Object
{
    protected $cacheTimeout = 5;

    protected static $_RPC = null;
    protected static function getRPC($type=null)
    {
        if (!self::$_RPC) {
            $conf = \Gini\Config::get('app.rpc')['gapper'];
            try {
                $rpc = \Gini\IoC::construct('\Gini\RPC', $conf['url']);
                $rpc->gapper->authorize($conf['client_id'],
$conf['client_secret']);
                self::$_RPC = $rpc;
            } catch (RPC\Exception $e) {
            }
        }

        return self::$_RPC;
    }

    /**
     * 按照配置设定的path 和 method 来进行RPC远程数据抓取
     *
     * @return mixed
     * @author Cheng.liu@geneegroup.com
     **/
    protected function fetchRPC($id)
    {
        return false;
    }

    public function db()
    {
        return false;
    }

    public function fetch($force = false)
    {
        if ($force || $this->_db_time == 0) {

            if (is_array($this->_criteria) && count($this->_criteria) > 0) {

                $criteria = $this->normalizeCriteria($this->_criteria);

                if (isset($criteria['id'])) {

                    $id = $criteria['id'];
                    $key = $this->name().'#'.$id;
                    $cacher = \Gini\Cache::of('orm');
                    $data = $cacher->get($key);
                    if (is_array($data)) {
                        \Gini\Logger::of('orm')->debug("cache hits on $key");
                    } else {
                        \Gini\Logger::of('orm')->debug("cache missed on $key");
                        $rdata = $this->fetchRPC($id);
                        if (is_array($rdata) && count($rdata) > 0) {
                            $data = $this->convertRPCData($rdata);
                            // set ttl to cacheTimeout sec
                            $cacher->set($key, $data, $this->cacheTimeout);
                        }
                    }

                    // 确认数据有效再进行id赋值
                    if (is_array($data) && count($data) > 0) {
                        $data['id'] = $id;
                    }
                }

            }

            $this->setData((array) $data);
        }

    }

    public function delete()
    {
        return false;
    }

    public function save()
    {
        return false;
    }

}
