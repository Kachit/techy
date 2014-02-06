<?php
    namespace Techy\Lib\Core\IO;

    class Response implements \Techy\Lib\Core\I\IResponse {

        /**
         * @param $name
         * @param null $value
         * @param null $expire
         * @param null $secure
         * @return mixed|void
         */
        public function cookie( $name, $value = null, $expire = null, $secure = null ){
            if(!headers_sent())
                setcookie( $name, $value, $expire, '/', null, $secure );
        }

        /**
         * @param $content
         * @param $replace
         * @param $code
         */
        public function header( $content, $replace = null, $code = null ){
            header( $content, $replace = null, $code = null );
        }

        /**
         * @var \Techy\Lib\Core\I\IResponse
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return \Techy\Lib\Core\I\IResponse
         */
        public static function instance(){
            if(!static::$Instance )
                static::$Instance = new static();
            return static::$Instance;
        }
    }
