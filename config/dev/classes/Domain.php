<?php
    namespace Techy\Config;

    class Domain extends \Techy\Lib\Core\Utilities\DomainConfig {
        protected static $subs = array(
        );

        protected function __construct(){
            static::$domain = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : 'admin.techy';
        }

        public function sub( $name ){
            return $this->main();
        }
    }
