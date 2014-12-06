<?php

namespace Gini\Module;

class GapperChat {

    public static function setup() {
        date_default_timezone_set(\Gini\Config::get('system.timezone') ?:
'Asia/Shanghai');

        class_exists('\Gini\Those');

        \Gini\Gapper\Client::init();

        $username = \Gini\Gapper\Client::getUserName();
        $me = a('user', ['username'=>$username]);
        $me->id or \Gini\Gapper\Client::logout();
        _G('ME', $me);

        $gid = $me->id ? (int) \Gini\Gapper\Client::getGroupID() : null;
        $group = a('group', $gid);
        _G('GROUP', $group);

        // set locale
        isset($_GET['locale']) and $_SESSION['locale'] = $_GET['locale'];
        $_SESSION['locale'] and \Gini\Config::set('system.locale', $_SESSION['locale']);
        \Gini\I18N::setup();

        setlocale(LC_MONETARY, (\Gini\Config::get('system.locale') ?: 'en_US') . '.UTF-8');

    }

}
