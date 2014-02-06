<?php
	namespace Techy\Lib\Core\I;

    /**
     * Dummy, implements by RegistryConfig
     * Defined methods could be call
     */

	interface IRegistry {

        /**
         * @abstract
         * @return ISession
         */
        public function Session();

        /**
         * @abstract
         * @return \Techy\Lib\Core\Utilities\I\IDate
         */
        public function Date();

        /**
         * @return \Techy\Lib\Core\NoSQL\Redis
         */
        public function Redis();

        /**
         * @return \Techy\Lib\Core\Database\I\IConnection
         */
        public function DatabaseConnection();

        /**
         * @return \Techy\Lib\Core\Database\I\ISpotConnection
         */
        public function DatabaseSpotConnection();
    }
	