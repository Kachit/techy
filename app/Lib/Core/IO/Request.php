<?php
    namespace Techy\Lib\Core\IO;

    class Request implements \Techy\Lib\Core\I\IRequest {

        public function getCookie( $var = null ){
            if( $var )
                return isset( $_COOKIE[$var] ) ? $_COOKIE[$var] : null;

            return $_COOKIE;
        }

        public function getGetParams( $var = null ){
            if( $var )
                return isset( $_GET[$var] ) ? $_GET[$var] : null;

            return $_GET;
        }

        public function getPostParams( $var = null ){
            if( $var )
                return isset( $_POST[$var] ) ? $_POST[$var] : null;

            return $_POST;
        }

        public function getServerVar( $var = null ){
            if( $var )
                return isset( $_SERVER[$var] ) ? $_SERVER[$var] : null;

            return $_SERVER;
        }

        /**
         * @var \Techy\Lib\Core\I\IRequest
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return \Techy\Lib\Core\I\IRequest
         */
        public static function instance(){
            if(!static::$Instance )
                static::$Instance = new static();
            return static::$Instance;
        }
    }
	