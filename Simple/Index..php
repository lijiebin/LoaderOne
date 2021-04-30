<?php

use Core\Kernel;

define('BASE_DIR', __DIR__);

$autoLoader = include_once 'LoaderOne\Autoloader.php';
$autoLoader->setBasePath(BASE_DIR);

$kernel = new Kernel();

$kernel->boot();

