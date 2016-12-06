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
        try {
            $cacheKey = "mall#authorize#{$clientID}#{$clientSecret}";
            $data = \Gini\Controller\API\Base::cache($cacheKey);
            if (false===$data) {
                $confs = \Gini\Config::get('mall.rpc');
                $conf  = $confs['node'];
                $rpc = \Gini\IoC::construct('\Gini\RPC', $conf['url']);
                $bool = $rpc->mall->authorize($clientID, $clientSecret);
                $data = $bool ? 1 : 0;
                \Gini\Controller\API\Base::cache($cacheKey, $data);
            }
        }
        catch (\Exception $e) {
            throw new \Gini\API\Exception('网络故障', 503);
        }

        if ($data) {
            $this->setCurrentApp($clientID);
            return session_id();
        }
        throw new \Gini\API\Exception('非法的APP', 404);
    }

}
