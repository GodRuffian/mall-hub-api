<?php

namespace Gini\Module;

class MallHubAPI
{

    public static function setup()
    {
        date_default_timezone_set(\Gini\Config::get('system.timezone') ?: 'Asia/Shanghai');
        class_exists('\Gini\Those');
        class_exists('\Gini\ThoseIndexed');
    }
}
