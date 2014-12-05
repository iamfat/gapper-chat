<?php

namespace Gini\Module;

class GapperChat {

    public static function setup() {
        date_default_timezone_set(\Gini\Config::get('system.timezone') ?:
'Asia/Shanghai');

        class_exists('\Gini\Those');

                // 获得当前的用户名, 设置全局变量ME
        $gapperToken = $_GET['gapper-token'];
        $gapperGroup = $_GET['gapper-group'];
        if ($gapperToken) {
            \Gini\Gapper\Client::logout();
            \Gini\Gapper\Client::loginByToken($gapperToken);
        }
        if ($gapperGroup &&
\Gini\Gapper\Client::getLoginStep()===\Gini\Gapper\Client::STEP_GROUP) {
            \Gini\Gapper\Client::chooseGroup($gapperGroup);
        }

        $username = \Gini\Gapper\Client::getUserName();
        $gid = (int) \Gini\Gapper\Client::getGroupID();

        if ($username) {
            $me = a('user', ['username'=>$username]);
            if (!$me->id) {
                \Gini\Gapper\Client::logout();
            }
            _G('ME', $me);
        }

        if ($gid) {
            $group = a('group', $gid);
            _G('GROUP', $group);
        }

        if (isset($_GET['locale'])) {
            $_SESSION['locale'] = $_GET['locale'];
        }

        if ($_SESSION['locale']) {
            \Gini\Config::set('system.locale', $_SESSION['locale']);
        }
        \Gini\I18N::setup();

        setlocale(LC_MONETARY, (\Gini\Config::get('system.locale') ?: 'en_US') . '.UTF-8');

    }

}
