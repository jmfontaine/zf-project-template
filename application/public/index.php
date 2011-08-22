<?php
if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
}

if (!defined('APPLICATION_ENV')) {
    $environment = getenv('APPLICATION_ENV');
    if (false === $environment) {
        $environment = 'production';
    }
    define('APPLICATION_ENV', $environment);
    unset($environment);
}

set_include_path(
    realpath(APPLICATION_PATH . '/../library') . PATH_SEPARATOR .
    get_include_path()
);

require_once 'Zend/Application.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    array(
    	'config' => array(
            APPLICATION_PATH . '/configs/application.ini',
            APPLICATION_PATH . '/configs/application-local.ini'
        )
    )
);
$application->bootstrap()
            ->run();