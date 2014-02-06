<?php
    namespace Techy\Lib\Core\Utilities\I;

    interface ILogger {

        /**
         * @abstract
         * @param \Exception $E
         */
        public function logException( \Exception $E );

        /**
         * @abstract
         * @param string $logFile
         * @param string $message
         * @param bool $putTimeLabel
         */
        public function log( $logFile, $message, $putTimeLabel = true );
    }
