<?php
    namespace Techy\Lib\Module\Acl;

    define('ACL_IMMUNE', 1 );
    define('ACL_EDIT_USERS', 2 );

    define('ACL_GROUP_ADMIN', 1 );

    class AclSettings {

        protected static $groups = array(
            ACL_GROUP_ADMIN => array(
                ACL_IMMUNE,
                ACL_EDIT_USERS,
            ),
        );

        public static function groups(){
            return array_keys( self::$groups );
        }

        public static function groupPrivileges( $gid ){
            return isset( self::$groups[$gid] ) ? self::$groups[$gid] : array();
        }
    }
