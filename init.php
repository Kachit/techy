<?php
    if(!defined( 'PLATFORM' ))
        define('PLATFORM', 'dev');

    date_default_timezone_set( 'Europe/Moscow' );
    mb_internal_encoding( 'UTF-8' );
    mb_http_output( 'UTF-8' );
    mb_language( 'en' );
    setlocale( LC_ALL, 'en_US.utf-8' );
    setlocale( LC_TIME, 'ru_RU.utf-8' );

    error_reporting( E_ALL | E_STRICT );

    require_once ROOT . 'const.php';
    require_once CONFIG_DIR . PLATFORM . '/const.php';

    // Load common exceptions
    require_once APP_DIR . 'Lib/Core/Exception/Common.php';

    // \Techy autoload
    require_once APP_DIR . 'Lib/Core/Autoloader.php';

    // AWS autoload
//    require_once EXTERNAL_DIR . 'aws/aws-autoloader.php';

    \Techy\Autoloader::register( PLATFORM );

    \Techy\Lib\Core\System\Registry::instance()->initialize( \Techy\Config\Registry::instance());
