<?php
    namespace Techy\Lib\Core\Service\PostgreSQL;

	use Techy\Lib\Core\Service\ObjectService;

    class PostgreSQLService extends ObjectService {

        protected static $SQL_BASE_CREATE = <<<SQL
    -- SQL_BASE_CREATE {{ t( table, spot ) }}
    INSERT INTO {{ t( table, spot ) }}
      ( {{ keys }} ) VALUES
      ( {{ vals }} )
      RETURNING *
SQL;
    }
