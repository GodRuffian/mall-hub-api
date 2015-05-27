<?php

namespace Gini\ORM;

class APP extends Object
{
    public $scope = 'array';
    public $client_id = 'string:40';

    protected static $db_index = [
        'unique:client_id'
    ];
}
