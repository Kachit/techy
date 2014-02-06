<?php
    namespace Techy\Lib\Core\I;

    interface ISessionAdapterStorage{

        public function readData( $key );

        public function writeData( $key, $data, $expire = null );

        public function exists( $key );

        public function getConnection();
    }
