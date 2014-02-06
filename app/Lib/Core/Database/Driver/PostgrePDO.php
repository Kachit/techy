<?php
    namespace Techy\Lib\Core\Database\Driver;

    use \Techy\Lib\Core\Exception\Database as E;

	class PostgrePDO extends Base {

        /**
         * @var \Techy\Lib\Core\Database\I\IConfig
         */
        private $Config;

        /**
         * @var \PDO
         */
        private $PDO;

        /**
         * @var \PDOStatement
         */
        private $Statement;

        /**
         * @param \Techy\Lib\Core\Database\I\IConfig $Config
         */
		public function __construct( \Techy\Lib\Core\Database\I\IConfig $Config ){
            $this->Config = $Config;
        }

        private function getPDO(){
            if( $this->PDO )
                return $this->PDO;
            $dbName = $this->Config->get( 'dbname' );
            $connectionString =
                'pgsql:host='. $this->Config->get( 'host' ) .
                ';port='. $this->Config->get( 'port' ) .
                ( $dbName ? ';dbname='. $dbName : '' );
            try {
                $this->PDO = new \PDO(
                    $connectionString,
                    $this->Config->get( 'user' ),
                    $this->Config->get( 'password' ),
                    array( 'charset' => $this->Config->get( 'charset' ))
                );
                $this->PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
                $this->PDO->setAttribute( \PDO::ATTR_CASE, 0 );
            }
            catch( \Exception $E ){
                $this->PDO = null;
                throw new E\Connection( 'Cannot connect to Postgres database '. $connectionString );
            }
            return $this->PDO;
        }

        /**
         * @param string $sql
         * @param array $args
         * @return void
         * @throws \Techy\Lib\Core\Exception\Database\SQLQuery
         */
        public function query( $sql, $args = array()){
            $PDO = $this->getPDO();
            $this->Statement = $PDO->prepare( $sql );
            try {
                if(!$this->Statement )
                    throw new E\SQLQuery( $PDO->errorCode() .': '. $PDO->errorInfo());
                if( isset( $args['pdo_data'] ))
                    foreach( $args['pdo_data'] as $arg => $value )
                        $this->Statement->bindValue( ':'. $arg, $value );
                if(!$this->Statement->execute())
                    throw new E\SQLQuery( $this->Statement->errorCode() .': '. $this->Statement->errorInfo());
            }
            catch( E\SQLQuery $E ){
                throw $E;
            }
            catch( \Exception $E ){
                throw new E\SQLQuery( $E->getMessage());
            }
        }

        /**
         * SQL must contain RETURNING <id field>
         *
         * @return bool|int
         */
        public function getInsertId(){
            $row = $this->Statement->fetch( \PDO::FETCH_NUM );
            if(!$row )
                return false;
            return reset( $row );
        }

        /**
         * @return int
         */
        public function getAffected(){
            return $this->Statement->rowCount();
        }

        /**
         * @param bool $assoc
         * @return array
         * @throws \Techy\Lib\Core\Exception\Database\QueryLogicError
         */
        public function fetchRow( $assoc = false ){
            if(!$this->Statement )
                throw new E\QueryLogicError( 'Must execute query before fetch row' );
            if( $assoc )
                return $this->Statement->fetch( \PDO::FETCH_ASSOC );
            else
                return $this->Statement->fetch( \PDO::FETCH_NUM );
        }

        /**
         * @param mixed $value
         * @return string
         */
        public function toScalar( $value ){
            // any other value can be just escaped
            if( is_null( $value ))
                return null;

            return $this->getPDO()->quote( strval( $value ));
        }

        /**
         * @param string $value
         * @return string
         */
        public function escapeString( $value ){
            return substr( $this->getPDO()->quote( strval( $value )), 1, -1 );
        }

        /**
         * @return bool
         */
        public function close(){
            if( $this->PDO )
                $this->PDO = null;
            return true;
        }
    }
	