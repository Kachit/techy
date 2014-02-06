<?php
    namespace Techy\Lib\Core\System\SessionAdapter\Storage;

    use Techy\Lib\Core\I\ISessionAdapterStorage;
    use Techy\Lib\Core\System\Registry;

    class Redis implements ISessionAdapterStorage{

        /**
         * @param $key
         * @return mixed
         */
        public function readData( $key ){
            $data = $this->getConnection()->get( $key );
            return $data;
        }

        /**
         * @param $key
         * @param $data
         * @param null $expire
         */
        public function writeData( $key, $data, $expire = null ){
            $this->getConnection()->set( $key, $data, $expire );
        }

        /**
         * @return \Redis
         */
        public function getConnection(){
            return Registry::instance()->Redis()->get();
        }

        /**
         * @param $key
         * @return bool
         */
        public function exists( $key ){
            return $this->getConnection()->exists( $key );
        }
    }
