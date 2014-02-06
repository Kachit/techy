<?php
    namespace Techy\Lib\Core\System;

    use Techy\Config\Dispatcher as ConfigDispatcher;
    use Techy\Lib\Core\Controller\Router;
    use Techy\Lib\Core\I\IDispatcher;
    use Techy\Lib\Core\I\IRequest;
    use Techy\Lib\Core\I\IRegistry;
    use Techy\Lib\Core\I\IResponse;
    use Techy\Lib\Core\I\IRouter;
    use Techy\Lib\Core\I\ILogger;
    use Techy\Lib\Core\I\ISpecialConfig;
    use Techy\Lib\Core\IO\Request;
    use Techy\Lib\Core\IO\Response;
    use Techy\Lib\Core\Utilities\I18n;
    use Techy\Lib\Core\Utilities\Logger;
    use Techy\Lib\Core\Utilities\SpecialConfig;

    class Application {
        /**
         * @var IRegistry
         */
        protected static $Instance;

        /**
         * @var IRequest
         */
        private $Request;

        /**
         * @var IResponse
         */
        private $Response;

        /**
         * @var IRouter
         */
        private $Router;

        /**
         * @var ILogger
         */
        private $Logger;

        /**
         * @var IDispatcher
         */
        private $Dispatcher;

        /**
         * @var ISpecialConfig
         */
        private $SpecialConfig;

        private function __construct(){}

        /**
         * @static
         * @return Application
         */
        public static function instance(){
            if(!static::$Instance){
                static::$Instance = new self();
            }
            return static::$Instance;
        }

        public function setRequest( IRequest $Request ){
            $this->Request = $Request;
            return $this;
        }

        public function getRequest(){
            if(!$this->Request ){
                $this->Request = Request::instance();
            }
            return $this->Request;
        }

        public function setResponse( IResponse $Response ){
            $this->Response = $Response;
            return $this;
        }

        public function getResponse(){
            if(!$this->Response ){
                $this->Response = Response::instance();
            }
            return $this->Response;
        }

        public function setDispatcher( IDispatcher $Dispatcher ){
            $this->Dispatcher = $Dispatcher;
            return $this;
        }

        /**
         * @return IDispatcher
         */
        public function getDispatcher(){
            if(!$this->Dispatcher ){
                $this->Dispatcher = ConfigDispatcher::instance();
            }
            return $this->Dispatcher;
        }

        public function setRouter( IRouter $Router ){
            $this->Router = $Router;
            return $this;
        }

        /**
         * @return IRouter
         */
        public function getRouter(){
            if(!$this->Router ){
                $this->Router = new Router(
                    $this->getConfigDir(),
                    $this->getRequest()->getServerVar( 'REQUEST_METHOD' ),
                    $this->getRequest()->getServerVar( 'REQUEST_URI' )
                );
            }
            return $this->Router;
        }

        public function getLogger(){
            if(!$this->Logger ){
                $this->Logger = new Logger( $this->getLogsDir());
            }
            return $this->Logger;
        }

        public function getSpecialConfig(){
            if(!$this->SpecialConfig ){
                $this->SpecialConfig = new SpecialConfig( $this->getConfigDir());
            }
            return $this->SpecialConfig;
        }

        public function getI18n(){
            return I18n::instance( $this->getSpecialConfig()->get( 'locale' ));
        }

        public function getName(){
            return $this->getDispatcher()->getApplicationName();
        }

        public function getLogsDir(){
            return LOGS_DIR . $this->getName() .'/'. PLATFORM .'/';
        }

        public function getCompiledDir(){
            return STATIC_COMPILED_DIR . $this->getName() .'/';
        }

        public function getControllerDir(){
            return $this->getAppDir() .'Controller/';
        }

        public function getConfigDir(){
            return $this->getAppDir() .'config/';
        }

        public function getViewDir(){
            return $this->getAppDir() .'views/';
        }

        public function getTemplatesDir(){
            return $this->getViewDir() . 'templates/';
        }

        public function getI18nDir(){
            return $this->getAppDir() . I18N_DIR;
        }

        public function getAppDir(){
            return APPLICATION_DIR . $this->getName() .'/';
        }

        public static function getRoutesDir( $applicationName ){
            return APPLICATION_DIR . $applicationName  .'/config/routes/';
        }

        public function redirect( $uri, $code = 302 ){
            $this->getResponse()->header( 'Location: '. $uri, null, $code );
            $this->stop();
        }

        public function run(){
            return $this->getRouter()->run();
        }

        public function stop(){
            exit(0);
        }
    }