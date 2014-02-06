<?php
    namespace Techy\Lib\Core\Database\I;

	interface ISpotConnection {

        /**
         * @abstract
         * @param string $name
         * @param int $spotId
         * @return IDatabase
         */
        public function get( $name, $spotId );
	}