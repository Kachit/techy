<?php
    namespace Techy\Lib\Service\User;

    use Techy\Lib\Core\Service\PostgreSQL\PostgreSQLService;

    class AuthService extends PostgreSQLService {

        const SQL_CREATE_USER = <<<SQL
    -- SQL_CREATE_USER
    SELECT create_user(
      {{ s(email) }},
      {{ s(password) }},
      {{ i(created) }}
    );
SQL;
        /**
         * Returns false on database error
         * Returns inserted id from database
         *
         * @param array $data
         * @return bool|string
         */
        public function create( array $data ){
            if( empty( $data ))
                return false;

            $result = $this->db()->selectField(
                self::SQL_CREATE_USER,
                $data
            );
            if( false === $result )
                return false;

            return intval( reset( $result )) ?: false;
        }

        const SQL_SELECT_USERS = <<<SQL
    -- SQL_SELECT_USERS
    SELECT *
      FROM users
SQL;
        public function select( array $data ){
            return $this->db()->selectTable( self::SQL_SELECT_USERS, $data );
        }

        const SQL_LOAD_AUTH = <<<SQL
    -- SQL_LOAD_AUTH
    SELECT *
      FROM users
      WHERE 1=1
      {{ IF user_id }}
        AND user_id = {{ i(user_id) }}
      {{ END }}
      {{ IF email }}
        AND email = {{ s(email) }}
      {{ END }}
      {{ IF password }}
        AND password = {{ s(password) }}
      {{ END }}
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
            return $this->db()->selectRecord(
                self::SQL_LOAD_AUTH,
                $data
            );
        }

        const SQL_GET_ACTIVATION_DATA = <<<SQL
    -- SQL_GET_ACTIVATION_DATA
    SELECT * FROM create_activation_code( {{ i(user_id) }}, {{ s(code) }} );
SQL;
        public function activationData( array $conditions ){
            return $this->db()->selectRecord(
                self::SQL_GET_ACTIVATION_DATA,
                $conditions
            );
        }

        const SQL_USER_ACTIVATE = <<<SQL
    -- SQL_USER_ACTIVATE
    SELECT activate( {{ i(user_id) }}, {{ s(code) }}, {{ i(activated) }} );
SQL;
        public function activate( array $conditions ){
            return $this->db()->selectField(
                self::SQL_USER_ACTIVATE,
                $conditions
            );
        }

        const SQL_GET_PASSWORD_RECOVERY_DATA = <<<SQL
    -- SQL_GET_PASSWORD_RECOVERY_DATA
    SELECT * FROM create_recovery_code( {{ i(user_id) }}, {{ s(code) }}, {{ i(created) }} );
SQL;
        public function recoveryData( array $conditions ){
            return $this->db()->selectRecord(
                self::SQL_GET_PASSWORD_RECOVERY_DATA,
                $conditions
            );
        }

        const SQL_CHECK_PASSWORD_RECOVERY_DATA = <<<SQL
    -- SQL_CHECK_PASSWORD_RECOVERY_DATA
    DELETE FROM users_recovery_codes
      WHERE ( user_id, code ) = ( {{ i(user_id) }}, {{ s(code) }} )
      RETURNING user_id;
SQL;
        public function checkRecoveryCode( array $conditions ){
            return $this->db()->selectField(
                self::SQL_CHECK_PASSWORD_RECOVERY_DATA,
                $conditions
            );
        }
    }
