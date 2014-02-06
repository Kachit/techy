<?php
    namespace Techy\Script;

    use Techy\Config\Assets;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\SwitchDispatcher;
    use Techy\Lib\Core\Utilities\Directory;
    use Techy\Lib\Core\Utilities\DynamicConfigFile;
    use Techy\Lib\Core\Utilities\I18n;

    class BootstrapScript extends Base {

        protected
            $version,
            $versions = array(),
            $appI18nDir,
            $appConfigDir,
            $appViewDir,
            $appCompiledDir;

        public function run(){
            $Dispatcher  = SwitchDispatcher::instance();
            $Application = Application::instance();
            $Application->setDispatcher( $Dispatcher );
            $DirectoryIterator = new \DirectoryIterator( APPLICATION_DIR );
            foreach( $DirectoryIterator as $Current ){
                /**
                 * @var \DirectoryIterator $Current
                 */
                if( $Current->isDot() || !$Current->isDir())
                    continue;

                $Dispatcher->setApplicationName( $Current->getBasename());
                $this->bootstrapApplication( $Application );
            }
        }

        protected function bootstrapApplication( Application $Application ){
            $this->appI18nDir = $Application->getI18nDir();
            $this->appConfigDir = $Application->getConfigDir();
            $this->appViewDir = $Application->getViewDir();
            $this->appCompiledDir = $Application->getCompiledDir();
            $this->version = time();

            Directory::instance()->createRecursive( $this->appCompiledDir . CSS_DIR );
            Directory::instance()->createRecursive( $this->appCompiledDir . JS_DIR . I18N_DIR );

            $DynamicConfigFile = DynamicConfigFile::instance();
            try {
                $this->versions = $DynamicConfigFile->read( Assets::VERSIONS_CONFIG, $this->appConfigDir );
            }
            catch ( \UnexpectedValueException $E ){
                $this->versions = array();
            }
            $this->bootstrapCss();
            $this->bootstrapJavascripts();

            $DynamicConfigFile->write( Assets::VERSIONS_CONFIG, $this->versions, $this->appConfigDir );
        }

        protected function renew( $type, $name ){
            $this->versions[$type][$name] = $this->version;
        }

        /**
         * CSS bootstrap
         */
        protected function bootstrapCss(){
            $src  = $this->appViewDir . ASSETS_LESS_DIR .'bootstrap.less';
            if(!file_exists( $src ))
                return;

            $temp = $this->appCompiledDir . CSS_DIR .'bootstrap_temp.css';
            $compiled  = $this->appCompiledDir . CSS_DIR .'bootstrap.css';
            exec( 'lessc --compress '. $src .' > '. $temp );

            if(!file_exists( $compiled )){
                rename( $temp, $compiled );
            }
            elseif( md5_file( $temp ) !== md5_file( $compiled )){
                unlink( $compiled );
                rename( $temp, $compiled );
            }
            else {
                unlink( $temp );
                return;
            }
            $this->renew( 'css', 'bootstrap' );
        }

        /**
         * JS concatenate and uglify
         */
        protected function bootstrapJavascripts(){
            $DynamicConfigFile = DynamicConfigFile::instance();
            try {
                $javascripts = $DynamicConfigFile->read( Assets::JS_CONFIG, $this->appConfigDir );
                $locales = $DynamicConfigFile->read( Assets::LOCALES_CONFIG, $this->appConfigDir );
            }
            catch( \Exception $E ){
                return;
            }
            if( $this->compileJavascript( $javascripts['base'], 'base', false ))
                $this->renew( 'js', 'base' );
            if( $this->compileJavascript( $javascripts['pages'], 'pages' ))
                $this->renew( 'js', 'pages' );

            foreach( $locales as $locale ){
                if( $this->compileLocalization( $locale ))
                    $this->renew( 'js', I18N_DIR . $locale );
            }
        }

        protected function compileJavascript( array $fromScripts, $toName, $assets = true ){
            $temp      = $this->appCompiledDir . JS_DIR . $toName .'_temp.js';
            $tempFinal = $this->appCompiledDir . JS_DIR . $toName .'_final.js';
            $compiled  = $this->appCompiledDir . JS_DIR . $toName .'.js';
            $sourceDir = $assets ? $this->appViewDir . ASSETS_JS_DIR : STATIC_DIR . JS_DIR;
            $File = new \SplFileObject( $temp, 'w' );
            foreach( $fromScripts as $js ){
                $File->fwrite( file_get_contents( $sourceDir . $js .'.js' ) ."\n");
            }
            exec( 'uglifyjs --verbose --no-copyright -o '. $tempFinal .' '. $temp );
            unlink( $temp );
            if(!file_exists( $compiled )){
                rename( $tempFinal, $compiled );
                return true;
            }
            elseif( md5_file( $tempFinal ) !== md5_file( $compiled )){
                unlink( $compiled );
                rename( $tempFinal, $compiled );
                return true;
            }
            else {
                unlink( $tempFinal );
                return false;
            }
        }

        protected function compileLocalization( $locale ){
            $I18n = I18n::instance( $locale );
            /**
             * @var \DirectoryIterator $File
             */
            $DirectoryIterator = new \DirectoryIterator( $this->appI18nDir . $locale );
            foreach( $DirectoryIterator as $File ){
                if( $File->isFile())
                    $I18n->pick( $File->getBasename('.php'));
            }
            $I18nFile = new \SplFileObject( $this->appCompiledDir . JS_DIR . I18N_DIR . $locale .'.js', 'w' );
            $I18nFile->fwrite( 'I18n.set('. json_encode( $I18n->getCache()) .');' );
            return true;
        }
    }
