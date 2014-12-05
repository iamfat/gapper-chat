<?php

namespace Gini\ORM;

class Group extends RObject
{
    public $name         = 'string:120';
    public $title        = 'string:120';
    public $abbr         = 'string:40';
    public $creator      = 'object:user';

    protected function fetchRPC($criteria)
    {
        try {
            return (array) self::getRPC()->gapper->group->getInfo($criteria);
        } catch (\Gini\RPC\Exception $e) {
            return [];
        }
    }

    public function convertRPCData(array $rdata)
    {
        $data = [];
        $data['id'] = $rdata['id'];
        $data['name'] = $rdata['name'];
        $data['title'] = $rdata['title'];
        $data['abbr'] = $rdata['abbr'];
        $data['creator'] = $rdata['creator'];
        $data['_extra'] = J(array_diff_key($rdata, array_flip(['id', 'name',
'title', 'abbr', 'creator'])));

        return $data;
    }

}
