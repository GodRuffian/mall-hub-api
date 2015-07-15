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

    public static function diagnose()
    {
        try {
            // check database: SHOW TABLES?
            $db = \Gini\Database::db();
            $ret = $db->query('SHOW TABLES');
            if (!$ret) {
                return ['Please config your database in raw/config/@'
                    .($_SERVER['GINI_ENV'] ?: 'production').'/database.yml!', ];
            }
        }
        catch (\Exception $e) {
            return ['Please config your database in raw/config/@'
                .($_SERVER['GINI_ENV'] ?: 'production').'/database.yml!', ];
        }

        // check gapper setting: is a valid app registered in gapper
        try {
            $conf = \Gini\Config::get('gapper.rpc');
            $rpc = \Gini\IoC::construct('\Gini\RPC', $conf['url']);
            $bool = $rpc->gapper->app->authorize((string)$conf['client_id'], (string)$conf['client_secret']);
            $bool = $bool ?: ($bool===false?:false);
            if (!$bool) {
                return ['Please check your rpc config in gapper.yml!'];
            }
        }
        catch (\Exception $e) {
                return ['gapper.rpc: '.$e->getMessage()];
        }
    }
}
