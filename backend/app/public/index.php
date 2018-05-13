<?php

require __DIR__ . '/../vendor/autoload.php';
require_once './../vendor/luracast/restler/vendor/restler.php';
use Luracast\Restler\Restler;

Defaults::$crossOriginResourceSharing = true;
Defaults::$accessControlAllowOrigin = '*';
Defaults::$accessControlAllowMethods = 'GET, POST, PUT, PATCH, DELETE, OPTIONS, HEAD';

$r = new Restler();
$r->addAPIClass('Api');
$r->handle();
