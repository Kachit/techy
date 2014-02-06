<?php
    namespace Techy\Lib\Service\User;

    use Techy\Lib\Core\Service\PostgreSQL\PostgreSQLService;

    class AdminService extends PostgreSQLService {

        const SQL_CREATE_ADMIN = <<<SQL
        -- SQL_CREATE_ADMIN
        SELECT create_admin(
          {{ s(login) }},
          {{ s(password) }}

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
                self::SQL_CREATE_ADMIN,
                $data
            );
            if( false === $result )
                return false;

            return intval( reset( $result ));
        }

        const SQL_GET_ADMIN_ACTIVATION_DATA = <<<SQL
        -- SQL_GET_ADMIN_ACTIVATION_DATA
        SELECT * FROM create_admins_activation_code( {{ i(user_id) }}, {{ s(code) }} );
SQL;
        public function activationData( array $conditions ){
            return $this->db()->selectRecord(
                self::SQL_GET_ADMIN_ACTIVATION_DATA,
                $conditions
            );
        }

        const SQL_SELECT_ADMINS = <<<SQL
        -- SQL_SELECT_ADMINS
        SELECT *
          FROM admins
SQL;
        public function select( array $data ){
            return $this->db()->selectTable( self::SQL_SELECT_ADMINS, $data );
        }

        const SQL_GET_ADMIN_PASSWORD_RECOVERY_DATA = <<<SQL
        -- SQL_GET_ADMIN_PASSWORD_RECOVERY_DATA
        SELECT * FROM create_admin_recovery_code( {{ i(user_id) }}, {{ s(code) }}, {{ i(created) }} );
SQL;
        public function recoveryData( array $conditions ){
            return $this->db()->selectRecord(
                self::SQL_GET_ADMIN_PASSWORD_RECOVERY_DATA,
                $conditions
            );

        }

        const SQL_CHECK_ADMIN_PASSWORD_RECOVERY_DATA = <<<SQL
        -- SQL_CHECK_ADMIN_PASSWORD_RECOVERY_DATA
        DELETE FROM admins_recovery_codes
          WHERE ( user_id, code ) = ( {{ i(user_id) }}, {{ s(code) }} )
          RETURNING user_id;
SQL;
        public function checkRecoveryCode( array $conditions ){
            return $this->db()->selectField(
                self::SQL_CHECK_ADMIN_PASSWORD_RECOVERY_DATA,
                $conditions
            );
        }
    }
