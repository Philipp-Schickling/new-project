<?php

// Set root path
$rootPath = realpath( dirname(__FILE__) . '/../' );
$rootPath = str_replace('\\', '/', $rootPath);
define('SYS_PATH_ROOT', $rootPath . '/');

// require constants
require_once( SYS_PATH_ROOT . 'src/modules/system/model/info.php' );

// Require bootstrap
require_once ( SYS_PATH_INIT . 'bootstrap.php' );

?>