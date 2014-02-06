<?php
    namespace Techy\Lib\Core\Database\I;

	interface IConfig {
        /**
         * @abstract
         * @param string $name
         * @return string
         */
        public function get( $name );

        /**
         * @abstract
         * @return IDriver
         */
        public function getDriver();

        /**
         * @abstract
         * @return ISQLTemplator
         */
        public function getTemplator();
	}
	