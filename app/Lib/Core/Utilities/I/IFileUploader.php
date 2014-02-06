<?php
    namespace Techy\Lib\Core\Utilities\I;

    interface IFileUploader {
        /**
         * @abstract
         * @return array
         */
        public function fetch();

        /**
         * @abstract
         * @return string
         */
        public function getTempPath();

        /**
         * @abstract
         * @param string $filePath
         */
        public function upload( $filePath );
    }
	