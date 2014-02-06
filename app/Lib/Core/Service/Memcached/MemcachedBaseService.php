<?php
    namespace Techy\Lib\Core\Service\Memcached;

	abstract class MemcachedBaseService {
        protected
            $spotId = 1,
            $name,
            $expiration = null;

        public function __construct( $name, $spotId = 1 ){
            if(!$name )
                throw new \LogicException( 'Name is not set in '. __CLASS__ );
            if( $spotId )
                $this->spotId = $spotId;
        }

        /**
         * @param int $id
         */
        final public function setSpotId( $id ){
            $this->spotId = 1; // todo
        }

        /**
         * @param int $seconds
         */
        final public function setExpiration( $seconds ){
            $this->expiration = intval( $seconds );
        }

        /**
         * @return \Memcached
         */
        final protected function client(){
            return \Techy\Lib\Core\NoSQL\Memcached::instance()->get( $this->spotId );
        }
    }
