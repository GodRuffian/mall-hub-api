<?php

namespace Gini\Controller\API;

class Mall extends \Gini\Controller\API
{
    
    public function actionAuthorize($appId, $appSecret)
    {
        $app = a('mall/app', ['app_id'=>$appId, 'app_secret'=>$appSecret]);
        if ($app->id) {
            $_SESSION['mall-api.app'] = $app->id;
        }
    }
    
    
}