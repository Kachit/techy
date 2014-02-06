<?php
    namespace Techy\Lib\Core\Service;

    class ObjectService extends AbstractService implements I\IObjectService {

        protected static $SQL_BASE_LOAD = <<<SQL
    -- SQL_BASE_LOAD {{ t( table, spot ) }}
    SELECT *
      FROM {{ t( table, spot ) }}
      WHERE {{ conditions }}
      LIMIT 1
SQL;
        /**
         * Returns false on database error
         * Returns array of record
         *
         * @param array $data
         * @return array|bool
         */
        public function load( array $data ){
            // data and table must be present
            if(!$data || !$this->table )
                return false;

            $Database = $this->db();
            // concatenate conditions
            $conditions = array();
            foreach( $data as $k => $value ){
                $condition = $Database->equalExpression( $k, $value );
                if( $condition )
                    $conditions[] = $condition;
            }
            if( empty( $conditions ))
                return false;
            // select row
            return $Database->selectRecord(
                static::$SQL_BASE_LOAD,
                array(
                    'table' => $this->table,
                    'spot' => $this->spotId,
                    'conditions' => implode( ' AND ', $conditions ),
                )
            );
        }

        protected static $SQL_BASE_CREATE = <<<SQL
    -- SQL_BASE_CREATE {{ t( table, spot ) }}
    INSERT INTO {{ t( table, spot ) }}
      ( {{ keys }} ) VALUES
      ( {{ vals }} )
SQL;
        /**
         * Returns false on database error
         * Returns inserted id from database
         *
         * @param array $data
         * @return bool|string
         */
        public function create( array $data ){
            // data and table must be present
            if( empty( $data ) || !$this->table )
                return false;

            $Database = $this->db();
            // concatenate keys and values
            $valuesString = $keysString = '';
            foreach( $data as $k => $value ){
                // scalar value
                $v = $Database->toScalar( $value );
                // null means no value
                if(!is_null( $v )){
                    $valuesString .= $v .',';
                    $keysString   .= $k .',';
                }
            }
            if(!$keysString )
                return false;
            // insert row
            return $Database->insert(
                static::$SQL_BASE_CREATE,
                array(
                    'table' => $this->table,
                    'spot' => $this->spotId,
                    'keys' => rtrim( $keysString, ',' ),
                    'vals' => rtrim( $valuesString, ',' ),
                )
            );
        }

        protected static $SQL_BASE_UPDATE = <<<SQL
    -- SQL_BASE_UPDATE {{ t( table, spot ) }}
    UPDATE {{ t( table, spot ) }}
      SET {{ updates }}
      WHERE {{ conditions }}
SQL;
        /**
         * Returns false on database error
         * Returns count of affe
         *
         * @param array $data
         * @param array $by
         * @return bool|int
         */
        public function update( array $data, array $by = null ){
            // data and table must be present
            // condition data must be present in var $by by default
            if(!$data || !$by || !$this->table )
                return false;

            $Database = $this->db();
            // concatenate data for update
            $updates = array();
            foreach( $data as $k => $value ){
                $update = $Database->equalExpression( $k, $value );
                if( $update )
                    $updates[] = $update;
            }
            // concatenate conditions
            $conditions = array();
            foreach( $by as $k => $value ){
                $condition = $Database->equalExpression( $k, $value );
                if( $condition )
                    $conditions[] = $condition;
            }
            if( empty( $updates ) || empty( $conditions ))
                return false;
            // update row
            return $Database->queryAffected(
                static::$SQL_BASE_UPDATE,
                array(
                    'table' => $this->table,
                    'spot' => $this->spotId,
                    'updates' => implode( ', ', $updates ),
                    'conditions' => implode( ' AND ', $conditions ),
                )
            );
        }

        protected static $SQL_BASE_DELETE = <<<SQL
    -- SQL_BASE_DELETE {{ t( table, spot ) }}
    DELETE FROM {{ t( table, spot ) }}
      WHERE {{ conditions }}
SQL;
        /**
         * Returns false on database error
         * Returns count of affected
         *
         * @param array $by
         * @return bool|int
         */
        public function delete( array $by ){
            // data and table must be present
            // condition data must be present in var $by by default
            if(!$by || !$this->table )
                return false;

            $Database = $this->db();
            // concatenate conditions
            $conditions = array();
            foreach( $by as $k => $value ){
                $condition = $Database->equalExpression( $k, $value );
                if( $condition )
                    $conditions[] = $condition;
            }
            if( empty( $conditions ))
                return false;
            // update row
            return $Database->queryAffected(
                static::$SQL_BASE_DELETE,
                array(
                    'table' => $this->table,
                    'spot' => $this->spotId,
                    'conditions' => implode( ' AND ', $conditions ),
                )
            );
        }
    }
