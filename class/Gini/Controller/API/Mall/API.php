<?php

namespace Gini\Controller\API\Mall;

abstract class API extends \Gini\Controller\API
{
    public function assertInScope($name)
    {
        $app = a('mall/app', $_SESSION['mall-api.app']);
        $scope = (array) $app->scope;
        if (!in_array($name, $scope)) {
            throw new \Gini\API\Exception('Access Denied', 401);
        }
    }
}
