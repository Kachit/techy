<?php
    namespace Techy\Lib\Core\NoSQL;

	class Memcached {

        /**
         * @var Memcached
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Memcached
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @var \Memcached
         */
        protected $MCache;

        /**
         * @param int $spotId
         * @return \Memcached
         */
        public function get( $spotId = 1 ){
            if(!isset( $this->MCache )){
                $this->MCache = new \Memcached();
                $this->MCache->addServer( 'localhost', \Techy\Lib\Core\Config\Constants::MEMCACHED_PORT );
            }
            return $this->MCache;
        }
	}