<?php
    namespace Techy\Lib\Core\Exception;

    class UploadError extends \Exception {

        const
            UNKNOWN = 1,
            NOT_EMPTY = 2,
            UNEXPECTED_TYPE = 3,
            WRONG_DIMENSIONS = 4
        ;

        public static function throwError( $code ){
            throw new UploadError( 'Error', $code );
        }
    }