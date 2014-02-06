<?php
    namespace Techy;

    use Techy\Lib\Core\IO\TestRequest;
    use Techy\Lib\Core\IO\TestResponse;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\SwitchDispatcher;

    if(!isset($_SERVER['TEST']))
        throw new \ErrorException( 'Test not specified' );

    define('ROOT', realpath( dirname( __FILE__ ) . '/../' ) . '/');
    define( 'PLATFORM', 'test' );

    require_once ROOT . 'init.php';
    require_once EXTERNAL_DIR . 'simpletest/autorun.php';

    function getTests( $dir ){
        $DirectoryIterator = new \RecursiveDirectoryIterator( $dir );
        $paths = array();
        foreach( $DirectoryIterator as $Current ){
            /**
             * @var \SplFileInfo $Current
             */
            if( $Current->getBasename() == '.' || $Current->getBasename() == '..')
                continue;
            if( $Current->isDir()){
                $paths = array_merge( $paths, getTests( $Current->getRealPath()));
            }
            elseif( $Current->isFile()){
                if( $Current->getExtension() === 'php' )
                    $paths[] = $Current->getRealPath();
            }
        }
        return $paths;
    }

    Application::instance()
        ->setDispatcher( SwitchDispatcher::instance())
        ->setRequest( TestRequest::instance())
        ->setResponse( new TestResponse());

    try{
        if( is_dir( TESTS_DIR . $_SERVER['TEST'] )){
            $paths = getTests( TESTS_DIR . $_SERVER['TEST'] );
            foreach( $paths as $path ){
                require_once $path;
            }
        }
        else{
            $testFile = $_SERVER['TEST'] .'TestCase.php';

            if(!file_exists( TESTS_DIR . $testFile ))
                throw new \ErrorException( 'Test file not exists' );

            require_once TESTS_DIR . $testFile;
        }
    }
    catch(\Exception $e){
        echo '<pre><b>Exception catched:</b> '. $e->getMessage()
            .'<br /><br />In <u>'. $e->getFile()
            .'</u>, at line <b>#'. $e->getLine()
            .'</b><br /><br />'. $e->getTraceAsString() .'</pre>';
    }

