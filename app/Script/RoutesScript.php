<?php
    namespace Techy\Script;

    use Techy\Config\Assets;
    use Techy\Lib\Core\Controller\Route;
    use Techy\Lib\Core\Controller\Router;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\SwitchDispatcher;
    use Techy\Lib\Core\Utilities\Directory;
    use Techy\Lib\Core\Utilities\DynamicConfigFile;

    class RoutesScript extends Base {

        protected
            $routes,
            $controllers,
            $appName,
            $appControllerDir;

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
                $this->bootstrapRoutes( $Application );
            }
        }

        protected function bootstrapRoutes( Application $Application ){
            $this->appName = $Application->getName();
            $this->appControllerDir = $Application->getControllerDir();

            $configsDir = $Application->getConfigDir();

            $this->routes = array();
            $this->controllers = array();
            $this->loadRoutes();
            foreach( $this->routes as &$methodRoutes ){
                ksort( $methodRoutes, SORT_DESC );
            }

            Directory::instance()->createRecursive( $configsDir );

            $DynamicConfigFile = DynamicConfigFile::instance();
            $DynamicConfigFile->write( Router::ROUTES_CONFIG, $this->routes, $configsDir );
            $DynamicConfigFile->write( Router::CONTROLLERS_CONFIG, $this->controllers, $configsDir );
        }

        /**
         * @param string $namespace
         * @return array
         * @throws \UnexpectedValueException
         */
        protected function loadRoutes( $namespace = '' ){
            $DirectoryIterator = new \DirectoryIterator(
                $this->appControllerDir . str_replace( '\\', '/', $namespace )
            );
            foreach( $DirectoryIterator as $Current ){
                /**
                 * @var \DirectoryIterator $Current
                 */
                if( $Current->isDot())
                    continue;

                $currentNamespace = $namespace .'\\'. $Current->getBasename( '.php' );
                if( $Current->isDir()){
                    $this->loadRoutes( $currentNamespace );
                }
                elseif( $Current->isFile()){
                    $className = $this->getClassName( $currentNamespace );
                    $appendRoutes = $this->getRoutes( $className );
                    foreach( $appendRoutes as $method => $methodRoutes ){
                        if(!isset( $this->routes[$method] ))
                            $this->routes[$method] = array();

                        $this->controllers[$currentNamespace][$method] = array();

                        $routes = array();
                        foreach( $methodRoutes as $Route ){
                            /**
                             * @var Route $Route
                             */
                            $route = $Route->getPackedParams();
                            $this->controllers[$currentNamespace][$method][$route[Route::PARAM_PAGE_NAME]] = $route;
                            $route[Route::PARAM_PAGE_METHOD] = $className;
                            $routes[$route[Route::PARAM_URL_TEMPLATE]] = $route;
                        }

                        $conflicts = array_intersect_key( $this->routes[$method], $routes );
                        if( $conflicts ){
                            var_dump($this->routes[$method]);
                            var_dump($routes);
                            throw new \UnexpectedValueException( 'Routes conflicts: "'. implode( '","', array_keys( $conflicts )) .'"' );
                        }

                        $this->routes[$method] += $routes;
                    }
                }
            }
        }

        protected function getRoutes( $className ){
            /**
             * @var \Techy\Lib\Core\Controller\AbstractController $Controller
             */
            $Controller = new $className();
            return $Controller->getRoutes();
        }

        protected function getClassName( $current ){
            return '\\Techy\\Application\\'. $this->appName .'\\Controller'. $current;
        }
    }
