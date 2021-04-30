<?php

namespace Core;

use Application\Controller\IndexController;

class Kernel
{
    public function __construct()
    {
        var_dump(__METHOD__);
    }
    
    public function boot()
    {
        return new IndexController();
    }
}