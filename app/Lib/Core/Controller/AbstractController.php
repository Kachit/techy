<?php
    namespace Techy\Lib\Core\Controller;

    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\Registry;
    use Techy\Lib\Core\Exception;

    abstract class AbstractController {

        const
            METHOD_GET = 'get',
            METHOD_POST = 'post',
            METHOD_DELETE = 'delete'
        ;

        /**
         * @var \Techy\Lib\Core\I\IHtmlView $View
         */
        protected $View;

        private
            $routes = [],
            $errors = [],
            $query = [],
            $unauthorizedPath
        ;

        /**
         * Overwrite to initialize class
         */
        public function __construct(){}

        /**
         * Overwrite to initialize routes
         */
        protected function initialize(){}

        /**
         * Overwrite to do generic actions before page actions
         */
        protected function preDispatch(){}

        /**
         * Overwrite to do generic actions after page actions
         */
        protected function postDispatch(){}

        final public function getRoutes(){
            if(!$this->routes ){
                $this->initialize();
            }
            return $this->routes;
        }

        final public function getApplication(){
            return Application::instance();
        }

        final public function getRequest(){
            return Application::instance()->getRequest();
        }

        final public function getResponse(){
            return Application::instance()->getResponse();
        }

        final public function getLogger(){
            return Application::instance()->getLogger();
        }

        final public function getSession(){
            return Registry::instance()->Session();
        }

        /**
         * Add routes in initialize method
         *
         * @param string $method - get/post/delete
         * @param string $pageMethod - page method name
         * @param string $pageName - page name (may be two or more names for one method, with different url templates and params)
         * @param string $urlTemplate - url template
         * @param array $params - query params names from url template
         * @return Route
         */
        final protected function addRoute( $method, $pageMethod, $pageName, $urlTemplate = '', $params = [] ){
            return $this->routes[$method][$pageName] = new Route( $pageMethod, $pageName, $urlTemplate, $params );
        }

        /**
         * Redirect to page with params
         *
         * @param string $controllerName
         * @param string $pageName
         * @param array $query
         */
        final protected function redirect( $controllerName, $pageName, array $query = array()){
            $Application = Application::instance();
            $Application->redirect( '/'. $Application->getRouter()->getRoute( 'get', $controllerName, $pageName, $query ));
        }

        /**
         * Set path to redirect for unauthorized query
         *
         * @param $path
         */
        final protected function setUnauthorizedPath( $path ){
            $this->unauthorizedPath = $path;
        }

        /**
         * Throw an error (generally by ajax, to show in form fields blocks)
         *
         * @param $errors
         * @throws \Techy\Lib\Core\Exception\RowValidation
         */
        final protected function throwValidationError( $errors ){
            $this->errors = $errors;
            throw new Exception\RowValidation();
        }

        /**
         * Throw an error (generally by ajax, to show in alert block)
         *
         * @param $error
         * @throws \Techy\Lib\Core\Exception\Client
         */
        final protected function throwClientError( $error ){
            $this->errors = [ $error ];
            throw new Exception\Client();
        }

        /**
         * Throw an error (generally by api, to show json with error message)
         *
         * @param $error
         * @throws \Techy\Lib\Core\Exception\Api
         */
        final protected function throwApiError( $error ){
            $this->errors = [ $error ];
            throw new Exception\Api();
        }

        /**
         * Get query string param
         *
         * @param string|null $name
         * @return mixed
         */
        final protected function queryParam( $name = null ){
            if(!$name )
                return $this->query;

            return array_key_exists( $name, $this->query ) ? $this->query[$name] : null;
        }

        /**
         * Calls from router
         *
         * @param $method
         * @param $pageName
         * @param array $query
         *
         * @return string
         * @throws \Exception
         */
        final public function run( $method, $pageName, array $query ){
            try {
                $routes = $this->getRoutes();
                if(!isset( $routes[$method], $routes[$method][$pageName] ))
                    throw new \UnexpectedValueException( 'Page '. $pageName .' for controller '. get_class( $this ) .' not exists' );

                /**
                 * @var Route $Route
                 */
                $Route = $routes[$method][$pageName];
                $view = $Route->getView();
                $className = '\\Techy\\Lib\\Core\\View\\'. $view;
                if(!class_exists( $className, true ))
                    throw new \UnexpectedValueException( 'View '. $view .' is not implemented' );
                $this->View = new $className();

                $page = $Route->getMethod();
                if(!method_exists( $this, $page ))
                    throw new \UnexpectedValueException( 'Page '. $pageName .' for controller '. get_class( $this ) .' is not implemented' );

                $this->query = $query;
                $this->preDispatch();

                $viewPreDispatch = 'preDispatch'. $view;
                if( method_exists( $this, $viewPreDispatch ))
                    $this->$viewPreDispatch();

                if(!$this->$page())
                    throw new Exception\e404();

                $this->postDispatch();
                $viewPostDispatch = 'postDispatch'. $view;
                if( method_exists( $this, $viewPostDispatch ))
                    $this->$viewPostDispatch();
            }
            catch( Exception\RowValidation $E ){
                if( $this->View instanceof \Techy\Lib\Core\I\IHtmlView ){
                    $this->View->set( 'error', reset( $this->errors ));
                    $this->View->template( 'error' );
                }
                else
                    $this->View->set( 'fields_errors', $this->errors );
            }
            catch( Exception\Client $E ){
                $this->View->set( 'error', reset( $this->errors ));
                if( $this->View instanceof \Techy\Lib\Core\I\IHtmlView )
                    $this->View->template( 'error' );
            }
            catch( Exception\Api $E ){
                $this->View->set( 'result', 'error' );
                $this->View->set( 'error', reset( $this->errors ));
            }
            catch( Exception\Page $E ){
                $header = $E->getMessage();
                $code = $E->getCode();
                if( $code === 401 ){
                    $this->getResponse()->header( 'Location: /'. $this->unauthorizedPath );
                    return '';
                }
                $this->getLogger()->logException( $E );
                $this->getResponse()->header( 'HTTP/1.0 '. $code .' '. $header );
                return '';
            }
            catch( \Exception $E ){
                throw $E;
            }

            return $this->View->render();
        }
    }