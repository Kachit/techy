<?php
    namespace Techy\Lib\Core\Database;

	class Connection implements I\IConnection {

        /**
         * @var Connection
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Connection
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        protected $databases = array();

        /**
         * @param string $name
         * @return \Techy\Lib\Core\Database\I\IDatabase
         */
        public function get( $name ){
            if(!isset( $this->databases[$name] )){
                $this->databases[$name] = new \Techy\Lib\Core\Database\Database( \Techy\Config\Database::instance( $name ));
            }
            return $this->databases[$name];
        }
	}