<?php
    namespace Techy\Lib\Core\Service;

    use Techy\Lib\Core\System\Registry;

    class AbstractService implements I\IService {
        protected
            $db = 'main',
            $table = null,
            $spotId = null,
            $transactionLevel = 0;

        public function __construct( $db = null, $table = null, $spotId = null ){
            if( $db )
                $this->db = $db;
            if( $table )
                $this->table = $table;
            if( $spotId )
                $this->spotId = $spotId;
        }

        final public function begin(){
            if(!$this->transactionLevel )
                $this->db()->query( 'BEGIN;' );
            $this->transactionLevel++;
        }

        final public function commit(){
            $this->transactionLevel--;
            if(!$this->transactionLevel )
                $this->db()->query( 'COMMIT;' );
        }

        final public function rollback(){
            $this->db()->query( 'ROLLBACK;' );
            $this->transactionLevel = 0;
        }

        public function closeConnection(){
            return $this->db()->close();
        }

        /**
         * @param null|string $name
         * @return \Techy\Lib\Core\Database\I\IDatabase
         */
        protected function db( $name = null ){
            return Registry::instance()->DatabaseConnection()->get( $name ? $name : $this->db );
        }
    }
