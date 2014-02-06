<?php
    namespace Techy\Lib\Core\Controller;

    use Techy\Lib\Core\Exception\e404;
    use Techy\Lib\Core\I\IRouter;
    use Techy\Lib\Core\Utilities\DynamicConfigFile;

    class Router implements IRouter {
        const
            PREG_DELIMITER = '#',

            ROUTES_CONFIG = 'routes',
            CONTROLLERS_CONFIG = 'controllers'
        ;

        private
            $url = '',
            $method = '',
            $routes = array(),
            $controllers = array(),
            $configDir;

        public function __construct( $configDir, $method, $url ){
            $this->configDir = $configDir;
            $this->method = strtolower( $method );

            $queryPos = strpos( $url, '?' );
            if( false !== $queryPos )
                $url = substr( $url, 0, $queryPos );
            $this->url = trim( $url, '/' );
        }

        /**
         * @return string
         */
        public function getMethod(){
            return $this->method;
        }

        /**
         * @return string
         */
        public function getUrl(){
            return $this->url;
        }

        /**
         * @param string $method
         * @param string $controllerName
         * @param string $pageName
         * @param array $query
         * @return string
         * @throws \UnexpectedValueException
         */
        public function getRoute( $method, $controllerName, $pageName, array $query = array()){
            $controllerName = '\\'. ucfirst( $controllerName );
            $controllers = $this->getControllers();
            if(!isset( $controllers[$controllerName][$method][$pageName] ))
                throw new \UnexpectedValueException( 'No such page "'. $pageName .'" in '. $controllerName .' for method '. $method );

            $route  = $controllers[$controllerName][$method][$pageName];
            $path   = $route[Route::PARAM_URL_TEMPLATE];
            $params = isset( $route[Route::PARAM_PARAMS] ) ? $route[Route::PARAM_PARAMS] : array();
            foreach( $params as $param ){
                $arg = isset( $query[$param] ) ? $query[$param] : '';
                $path = preg_replace( '#<[^>]*>#', $arg, $path, 1 );
            }
            return $path;
        }

        /**
         * @param string $method
         * @return array
         */
        public function getRoutes( $method = null ){
            if(!$this->routes ){
                $this->routes = DynamicConfigFile::instance()->read( self::ROUTES_CONFIG, $this->configDir );
            }
            if( $method ){
                if(!isset( $this->routes[$method] ))
                    return array();

                return $this->routes[$method];
            }

            return $this->routes;
        }

        /**
         * @return array
         */
        public function getControllers(){
            if(!$this->controllers ){
                $this->controllers = DynamicConfigFile::instance()->read( self::CONTROLLERS_CONFIG, $this->configDir );
            }
            return $this->controllers;
        }

        /**
         * @throws \Techy\Lib\Core\Exception\e404
         * @return string
         */
        public function run(){
            $routes = $this->getRoutes( $this->method );

            foreach( $routes as $preg => $route ){
                $className = $route[Route::PARAM_PAGE_METHOD];
                if( $this->preg( $preg, $this->url, $matches )){
                    $params = isset( $route[Route::PARAM_PARAMS]) ? $route[Route::PARAM_PARAMS] : array();
                    if( count( $matches ) !== count( $params )) {
                        throw new e404( 'Wrong query. Invalid number of parameters.' );
                    }
                    $query = $matches ? array_combine( $params, $matches ) : array();
                    /**
                     * @var AbstractController $Controller
                     */
                    $Controller = new $className();
                    return $Controller->run( $this->method, $route[Route::PARAM_PAGE_NAME], $query );
                }
            }

            throw new e404( 'Wrong query. Route "'. $this->url .'" not exists' );
        }

        /**
         * @param string $route
         * @param string $url
         * @param null|array $matches
         * @return bool
         */
        protected function preg( $route, $url, &$matches = null ){
            $route = strtr( $route, array(
                '<str>'  => '([\w'. \Techy\ALPHABET .']+)',
                '<estr>' => '([\w\s\-'. \Techy\ALPHABET .']*)',
                '<int>'  => '(\d+)',
            ));
            $route = preg_replace( '#<([^>]*)>#', '($1)', $route );
            $preg = self::PREG_DELIMITER .'^'. $route .'$'. self::PREG_DELIMITER;
            if(!preg_match( $preg, $url, $matches ))
                return false;
            if( $matches )
                array_shift( $matches );
            return true;
        }
    }