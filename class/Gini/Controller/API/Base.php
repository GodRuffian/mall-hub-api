<?php

/**
* @file Base.php
* @brief 为所有APP提供通用的方法
* @author PiHiZi <pihizi@msn.com>
* @version 0.1.0
* @date 2015-05-27
 */
namespace Gini\Controller\API;

abstract class Base extends \Gini\Controller\API
{
    // session key
    private static $_sessionKey = 'mall-api.appid';

    public function __construct()
    {
        $this->assertAuthorized();
    }

    /**
        * @brief 设置当前请求的APP信息
        *
        * @param $id
        *
        * @return 
     */
    public function setCurrentApp($clientID)
    {
        $_SESSION[self::$_sessionKey] = $clientID;
    }

    /**
        * @brief 获取当前请求的APP信息
        *
        * @return 
     */
    public function getCurrentApp()
    {
        $clientID = $_SESSION[self::$_sessionKey];
        $app = a('app', ['client_id'=>$clientID]);
        return $app;
    }

    /**
        * @brief 断言app已经通过验证
        *
        * @return 
     */
    public function assertAuthorized()
    {
        $app = $this->getCurrentApp();
        if (!$app->id) {
            throw new \Gini\API\Exception('APP没有通过验证', 404);
        }
    }

    /**
        * @brief 断言当前APP是有指定的Scope
        *
        * @param $name
        *
        * @return 
     */
    public function assertInScope($name)
    {
        $app = $this->getCurrentApp();
        $scope = (array) $app->scope;
        if (!in_array($name, $scope)) {
            throw new \Gini\API\Exception('Access Denied', 401);
        }
    }
}
