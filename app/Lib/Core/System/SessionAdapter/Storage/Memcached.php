<?php
    namespace Techy\Lib\Core\System\SessionAdapter\Storage;

    use Techy\Lib\Core\I\ISessionAdapterStorage;
    use Techy\Lib\Core\System\Registry;

    class Memcached implements ISessionAdapterStorage{

        /**
         * @param $key
         * @return mixed
         */
        public function readData( $key ){
            $data = $this->getConnection()->get( $key );
            if( $data ){
                $data = unserialize( $data );
            }
            return $data;
        }

        /**
         * @param $key
         * @param $data
         * @param null $expire
         */
        public function writeData( $key, $data, $expire = null ){
            $this->getConnection()->set( $key, serialize( $data ), $expire );
        }

        /**
         * @return \Memcached
         */
        public function getConnection(){
            return Registry::instance()->Memcached()->get();
        }

        /**
         * @param $key
         * @return bool
         */
        public function exists( $key ){
            $key = $this->getConnection()->get( $key );
            return ( $key === false ? false : true );
        }
    }
