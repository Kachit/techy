<?php
	namespace Techy\Lib\Core\Data\I;

	interface IEnum {
        /**
         * @abstract
         * @param $option
         * @return string
         */
        public function name( $option );

        /**
         * @abstract
         * @param string $name
         * @return int
         */
        public function searchValue( $name );
    }
	