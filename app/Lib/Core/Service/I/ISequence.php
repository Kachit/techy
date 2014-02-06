<?php
	namespace Techy\Lib\Core\Service\I;

	interface ISequence {
        /**
         * @abstract
         * @return int
         */
        public function allocateId();

        /**
         * @abstract
         * @return int
         */
        public function lastId();
	}
	