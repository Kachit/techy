<?php
    namespace Techy\Lib\Core\Database;

	class TempConfig extends Config {

        /**
         * @static
         * @param $name
         * @param array $config
         * @return Config|TempConfig
         * @throws \UnexpectedValueException
         */
        public static function instance( $name, $config = array()){
            return new static( $config );
        }
	}