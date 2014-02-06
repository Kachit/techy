<?php
    namespace Techy;

    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\Dispatcher;

    if( !isset( $_SERVER[ 'SCRIPT' ] ) )
        throw new \ErrorException( 'Script not specified' );

    $script = str_replace( '/', '\\', $_SERVER[ 'SCRIPT' ] );
    $scriptClass = '\\Techy\\Script\\' . $script . 'Script';

    define( 'ROOT', realpath( dirname( __FILE__ ) . '/../' ) . '/' );
    define( 'PLATFORM', isset( $_SERVER[ 'PLATFORM' ] ) ? $_SERVER[ 'PLATFORM' ] : 'dev' );

    echo PHP_EOL;
    try{
        require_once ROOT . 'init.php';

        Application::instance()->setDispatcher( Dispatcher::instance() );

        if( !class_exists( $scriptClass ) )
            throw new \ErrorException( 'Script not exists "' . $script . '"' );

        $argv = array_slice( $_SERVER[ 'argv' ], 2 );

        /**
         * @var \Techy\Script\Base $Script
         */
        $Script = new $scriptClass( $argv );
        $Script->run();

        echo "\33[30;1;32m[ Done ]\33[0m";
    } catch( \Exception $E ){
        echo $E->getMessage() . PHP_EOL . PHP_EOL
            . 'In ' . $E->getFile()
            . ', at line #' . $E->getLine() . PHP_EOL . PHP_EOL
            . $E->getTraceAsString();
    }
    echo PHP_EOL . PHP_EOL;
