<?php


require __DIR__.'/Autoload.php';

$Autoload = new \Rundiz\Pagination\Tests\Autoload();
$Autoload->addNamespace('Rundiz\\Pagination\\Tests', __DIR__);
$Autoload->addNamespace('Rundiz\\Pagination', dirname(dirname(__DIR__)).'/Rundiz/Pagination');
$Autoload->register();