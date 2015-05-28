<?php

namespace Gini\Controller\CLI\Hub\API;

class Scope extends \Gini\Controller\CLI
{
    public function __index($args)
    {
        $this->actionHelp();
    }

    public function actionHelp()
    {
        echo "Available commands:\n";
        echo "  gini hub api scope add\n";
    }


    private function getScopes()
    {
        $class = '\Gini\\' . str_replace('-', '\\', APP_ID);
        $handler = \Gini\IoC::construct($class);
        return $handler->getScopes();
    }

    public function actionAdd()
    {
        while (!$clientID) {
            $data = $this->getData([
                'clientID'=> [
                    'title'=> '请输入gapper-server为APP颁发的client_id'
                ]
            ]);
            $clientID = $data['clientID'];
        }

        $scopes = $this->getScopes();
        $this->show('以下scope可选');
        foreach ($scopes as $scope) {
            $this->show("\t$scope");
        }

        while (!$scope) {
            $data = $this->getData([
                'scope'=> [
                    'title'=> '指定新增的scope'
                ]
            ]);
            $scope = $data['scope'];
        } 

        $file = APP_PATH . '/' . DATA_DIR . '/scope/' . $clientID . '.yml';
        $scopes = [];
        if (file_exists($file)) {
            $scopes = (array) \yaml_parse_file($file);
        }

        if (!in_array($scope, $scopes)) {
            $scopes[] = $scope;
            $bool = \yaml_emit_file($file, $scopes);
        }
        else {
            $bool = true;
        }

        if ($bool===true) {
            $this->show('添加scope成功');
            return;
        }

        $this->showError('添加scope失败');

    }

    private function getData($data)
    {
        $result = [];
        foreach ($data as $k => $v) {
            $tmpTitle = $v['title'];
            $tmpEG = $v['example'] ? " (e.g \e[31m{$v['example']}\e[0m)" : '';
            $tmpDefault = $v['default'] ? " default value is \e[31m{$v['default']}\e[0m" : '';
            $tmpData = readline($tmpTitle . $tmpEG . $tmpDefault . ': ');
            if (isset($v['default']) && !$tmpData) {
                $tmpData = $v['default'];
            }
            if (isset($tmpData) && $tmpData!=='') {
                $result[$k] = $tmpData;
            }
        }

        return $result;
    }

    private function surround($string)
    {
        return "\e[31m" . $string . "\e[0m";
    }

    private function show($msg)
    {
        echo $msg . "\n";
    }

    private function showError($msg)
    {
        $this->show($this->surround($msg));
    }
}
