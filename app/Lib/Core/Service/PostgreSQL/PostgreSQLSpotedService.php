<?php
    namespace Techy\Lib\Core\Service\PostgreSQL;

    class PostgreSQLSpotedService extends PostgreSQLService {

        protected $spotId;

        /**
         * @param $spotId
         * @return int
         */
        public function setSpotId( $spotId ){
            return $this->spotId = $spotId;
        }

        /**
         * @param null|string $name
         * @return \Techy\Lib\Core\Database\I\IDatabase
         */
        protected function db( $name = null ){
            return \Techy\Lib\Core\System\Registry::instance()->DatabaseSpotConnection()->get( $name ?: $this->db, $this->spotId );
        }
    }
