<?php
    namespace Techy\Script\Database;

    abstract class ActualizeDatabaseSpotScript extends ActualizeDatabaseScript {

        protected $dbName;
        protected $spot = 1;

        public function setSpot( $spot ){
            $this->spot = $spot;
        }

        public function run(){
            $this->version = intval( file_get_contents( SQL_DIR .'VERSION' ));
            $this->Database = $this->spotDb( $this->dbName, $this->spot );
            $this->Config = \Techy\Config\DatabaseSpot::instance( $this->dbName, $this->spot );
            $this->processDb();
//            $this->backup();
        }

        protected function getBackupName(){
            return SQL_DIR .'backups/v'. $this->version .'__'. $this->dbName .'_'. $this->spot .'.sql';
        }

        protected function getDbName(){
            return $this->Config->get( 'dbname' ) .'_'. $this->spot;
        }
    }
