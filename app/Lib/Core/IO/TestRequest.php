<?php
    namespace Techy\Lib\Core\IO;

    class TestRequest implements \Techy\Lib\Core\I\IRequest {

        private
            $_cookie = array(),
            $_get = array(),
            $_post = array(),
            $_server = array();


        public function getCookie( $var = null ){
            if( $var )
                return isset( $this->_cookie[$var] ) ? $this->_cookie[$var] : null;

            return $this->_cookie;
        }

        public function getGetParams( $var = null ){
            if( $var )
                return isset( $this->_get[$var] ) ? $this->_get[$var] : null;

            return $this->_get;
        }

        public function getPostParams( $var = null ){
            if( $var )
                return isset( $this->_post[$var] ) ? $this->_post[$var] : null;

            return $this->_post;
        }

        public function getServerVar( $var = null ){
            if( $var )
                return isset( $this->_server[$var] ) ? $this->_server[$var] : null;

            return $this->_server;
        }

        /**
         * @var \Techy\Lib\Core\I\IRequest
         */
        protected static $Instance;

        protected function __construct(){}

        public static function instance(){
            if(!self::$Instance )
                self::$Instance = new static();
            return self::$Instance;
        }

        public static function reset(){
            return self::$Instance = new static();
        }

        public function setCookie( array $cookie ){
            $this->_cookie = $cookie;
            return $this;
        }

        public function setGet( array $get ){
            $this->_get = $get;
            return $this;
        }

        public function setPost( array $post ){
            $this->_post = $post;
            return $this;
        }

        public function setServerVar( $var, $value ){
            $this->_server[$var] = $value;
            return $this;
        }
    }
	