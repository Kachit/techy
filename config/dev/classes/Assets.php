<?php
    namespace Techy\Config;

    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\Utilities\DynamicConfigFile;
    use Techy\Lib\Core\View\Config;

    class Assets extends Config {
        protected static function getStyles(){
            return array(
                'less' => array(
                    'bootstrap_less.php' => filemtime( Application::instance()->getViewDir() . ASSETS_LESS_DIR .'bootstrap.less' )
                ),
            );
        }

        protected static function getJs(){
            $Application = Application::instance();
            $viewDir = $Application->getViewDir();
            $compiledDir = $Application->getCompiledDir();
            $locale = $Application->getSpecialConfig()->get( 'locale' );
            $scripts = array();
            $javascripts = DynamicConfigFile::instance()->read( self::JS_CONFIG, $Application->getConfigDir());
            $javascripts['base'][] = 'assets/less';
            foreach( $javascripts['base'] as $js ){
                $path = JS_DIR . $js .'.js';
                $time = filemtime( STATIC_DIR . $path );
                if( $time )
                    $scripts[$path] = $time;
            }
            // locales
            $path = JS_DIR . I18N_DIR . $locale .'.js';
            $time = filemtime( $compiledDir . $path );
            if( $time )
                $scripts[$path] = $time;
            foreach( $javascripts['pages'] as $js ){
                $path = ASSETS_JS_DIR . $js .'.js';
                if (!file_exists($viewDir . $path )){
                    continue;
                }
                $time = filemtime( $viewDir . $path );
                if( $time )
                    $scripts[$path] = $time;
            }
            return $scripts;
        }
    }
