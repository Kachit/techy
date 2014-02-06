<?php
	namespace Techy\Lib\Core\Data\I;

	interface IObject extends \ArrayAccess, IData, IExportable {

        /**
         * @abstract
         * @param array $a
         * @return IObject
         */
        public function fetch( array $a );

        /**
         * @abstract
         * @param array $a
         * @return IObject
         */
        public function merge( array $a );

        /**
         * @abstract
         * @return array
         */
        public function keys();

        /**
         * @abstract
         * @param array $a
         * @return array
         */
        public function fieldsFilter( array $a );
	}
	