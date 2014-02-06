<?php
    namespace Techy\Lib\Core\NoSQL;

	class Redis {

        /**
         * @var Redis
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Redis
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        protected $Redis = null;

        /**
         * @param int $spotId
         * @return \Redis
         * @throws \UnexpectedValueException
         */
        public function get( $spotId = 1 ){
            if(!$this->Redis ){
                $this->Redis = new \Redis();
                $this->Redis->connect( REDIS_LOCAL_HOST, REDIS_LOCAL_PORT );
                $this->Redis->select( REDIS_LOCAL_DB );
                $this->Redis->setOption( \Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP );
            }
            return $this->Redis;
        }
	}