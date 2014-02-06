<?php
    namespace Techy\Lib\Core\Utilities\Upload;

    use Techy\Config\Domain;

    class UrlBuilder {
        protected static $resourceName = 'resource';

        /**
         * @param string $path
         * @return string
         * @throws \UnexpectedValueException
         */
        public function url( $path ){
            return Domain::instance()->sub( static::$resourceName ) . $path;
        }

        /**
         * @param string $type
         * @return bool
         */
        public function checkType( $type ){
            return true;
        }

        /**
         * @return string
         */
        public function dir(){
            return UPLOAD_DIR;
        }
    }