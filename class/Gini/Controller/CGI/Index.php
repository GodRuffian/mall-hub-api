<?php

namespace Gini\Controller\CGI;

class Index extends \Gini\Controller\CGI {
    
    public function __index() {
        return new \Gini\CGI\Response\JSON([
            "service" => APP_ID,
            "status" => "running",
        ]);
    }
    
}