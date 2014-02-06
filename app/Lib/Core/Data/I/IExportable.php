<?php
	namespace Techy\Lib\Core\Data\I;

	interface IExportable {
        /**
         * @abstract
         * @return array
         */
        public function export();
	}
	