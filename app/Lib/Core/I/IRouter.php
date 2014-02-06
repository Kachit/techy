<?php
	namespace Techy\Lib\Core\I;

	interface IRouter {

        /**
         * @abstract
         * @return string
         */
        public function getMethod();

        /**
         * @abstract
         * @return string
         */
        public function getUrl();

        /**
         * @abstract
         * @param string $method
         * @param string $controllerName
         * @param string $pageName
         * @param array $query
         * @return string
         */
        public function getRoute( $method, $controllerName, $pageName, array $query = array());

        /**
         * @abstract
         * @return string
         */
        public function run();
    }
	