<?php

namespace Gini\Controller\API;

class Mall extends \Gini\Controller\API\Base
{
    /**
        * @brief 重写构造函数，避免authorize断言判断
        *
        * @return 
     */
    public function __construct()
    {
    }

    public function actionAuthorize($clientID, $clientSecret)
    {
        $conf = \Gini\Config::get('gapper.rpc');
        $rpc = \Gini\IoC::construct('\Gini\RPC', $conf['url']);
        $bool = $rpc->gapper->app->authorize($clientID, $clientSecret);
        if ($bool) {
            $this->setCurrentApp($clientID);
            return session_id();
        }
        throw new \Gini\API\Exception('非法的APP', 404);
    }
    
}
