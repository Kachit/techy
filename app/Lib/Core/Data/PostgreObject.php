<?php
    namespace Techy\Lib\Core\Data;

    use Techy\Lib\Core\Service\PostgreSQL\PostgreSQLService;

    abstract class PostgreObject extends DatabaseObject {

        protected function getService(){
            if(!$this->Service )
                $this->Service = new PostgreSQLService( static::$db, static::$table );

            return $this->Service;
        }
    }
