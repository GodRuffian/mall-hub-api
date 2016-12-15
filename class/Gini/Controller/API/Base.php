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
        * @brief 非正常退出
        *
        * @param $message
        * @param $code
        *
        * @return 
     */
    public function quit($message, $code=1)
    {
        throw new \Gini\API\Exception($message, $code);
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
        return $clientID;
    }

    /**
        * @brief 断言app已经通过验证
        *
        * @return 
     */
    public function assertAuthorized()
    {
        $clientID = $this->getCurrentApp();
        if (!$clientID) {
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
        $clientID = $this->getCurrentApp();
        $file = APP_PATH . '/' . DATA_DIR . '/scope/' . $clientID . '.yml';
        if (!file_exists($file)) {
            throw new \Gini\API\Exception('File Not Found', 401);
        }

        $scopes = (array) \yaml_parse_file($file);
        array_walk($scopes, function(&$value) {
            $value = constant('\Gini\Mall\Hub\Vendor::' . $value);
        });
        if (!in_array($name, $scopes)) {
            throw new \Gini\API\Exception('Access Denied', 401);
        }
    }

    public static function cache($key, $value=null) 
    {
        $cacher = \Gini\Cache::of('defalut');
        if (is_null($value)) {
            return $cacher->get($key);
        }
        $config = \Gini\Config::get('cache.default');
        $timeout = @$config['timeout'];
        $timeout = is_numeric($timeout) ? $timeout : 500;
        $cacher->set($key, $value, $timeout);
    }
}
