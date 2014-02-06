<?php
    namespace Techy\Lib\Core\System;

    define('ROOT', realpath( dirname( __FILE__ ) . '/../' ) . '/');
    define('PLATFORM', 'dev');

    require_once ROOT . 'init.php';

    $Application = Application::instance()->setDispatcher( Dispatcher::instance());

    $lessDir = $Application->getViewDir() . ASSETS_LESS_DIR;

    $bootstrap = file_get_contents( $lessDir .'bootstrap.less' );

    preg_match_all( '#import "([\w\-\.]+\.less)"#', $bootstrap, $matches );

    foreach( $matches[1] as $filename ){
        $bootstrap = str_replace( $filename, '/'. ASSETS_LESS_DIR . $filename .'?'. filemtime( $lessDir . $filename ), $bootstrap );
    }

    echo '/* test */';
    echo $bootstrap;