<?php
    namespace Techy\Lib\Core\System;
//trace
    define('ROOT', realpath( dirname( __FILE__ ) . '/../' ) . '/');
    define('PLATFORM', isset($_SERVER['PLATFORM']) ? $_SERVER['PLATFORM'] : 'dev');

    try{
        require_once ROOT . 'init.php';

        echo Application::instance()->run();
    }
    catch( \Exception $E ){
        echo '<pre><b>Exception catched:</b> ' . $E->getMessage()
            . '<br /><br />In <u>' . $E->getFile()
            . '</u>, at line <b>#' . $E->getLine()
            . '</b><br /><br />' . $E->getTraceAsString() . '</pre>';
    }
