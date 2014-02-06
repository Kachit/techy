<?php
    namespace Techy\Lib\Core\Utilities;

    use Techy\Lib\Core\I\ILogger;

    class Logger implements ILogger {
        /**
         * @var Logger
         */
        protected static $Instance;
        protected static $path;

        public function __construct( $path ){
            static::$path = $path;
        }

        /**
         * @static
         * @param \Exception $E
         */
        public function logException( \Exception $E ){
            $message = '#'. $E->getCode() .' at '. $E->getLine() .' in '. $E->getFile() .': '. $E->getMessage() ."\n\t"
                . preg_replace( '#\r?\n#', "\n\t", $E->getTraceAsString());
            static::log( 'error', $message );
        }

        /**
         * @static
         * @param string $logFile
         * @param string $message
         * @param bool $putTimeLabel
         */
        public function log( $logFile, $message, $putTimeLabel = true ){
            $h = static::fopen( $logFile );
            if( $h ){
                if( $putTimeLabel )
                    fwrite( $h, '['. date( 'Y/m/d H:i:s' ) .'] ' );
                fwrite( $h, $message ."\n\n" );
                fclose( $h );
            }
        }

        /**
         * @static
         * @param string $logFile
         * @return resource
         */
        protected function fopen( $logFile ){
            $logPath = static::$path . $logFile .'.log';
            return fopen( $logPath, 'a' );
        }
    }
