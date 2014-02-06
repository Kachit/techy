<?php
    namespace Techy\Lib\Core\System;

    define('ROOT', realpath( dirname( __FILE__ ) . '/../' ) . '/');
    define('PLATFORM', isset( $_SERVER['PLATFORM'] ) ? $_SERVER['PLATFORM'] : 'prod');

    try{
        require_once ROOT .'init.php';

        echo Application::instance()->run();
    }
    catch( \UnexpectedValueException $E ){
        Application::instance()->getLogger()->log( 'fatal-error', $E->getMessage());
        header( 'Internal Server Error', true, 500 );
        exit;
    }
    catch( \Exception $E ){
        Application::instance()->getLogger()->logException( $E );
        header( 'Internal Server Error', true, 500 );
        exit;
    }
