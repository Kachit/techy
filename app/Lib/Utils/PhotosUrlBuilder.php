<?php
    namespace Techy\Lib\Utils;

    use Techy\Config\Domain;
    use Techy\Lib\Core\Utilities\Upload\UrlBuilder;

    class PhotosUrlBuilder extends UrlBuilder {

        protected static $resourceName = 'cover';

        /**
         * @param string $path
         * @return string
         * @throws \UnexpectedValueException
         */
        public function url( $path ){
            return Domain::instance()->sub( self::$resourceName ) . $path;
        }

        /**
         * @param string $tmpPath
         * @return string
         */
        public function generatePath( $tmpPath ){
            $path = md5( $tmpPath );
            return 'photos/'. substr( $path, 0, 2 ) .'/'. substr( $path, 2, 2 ) .'/'.
                preg_replace( '#^.*/(php)?([^/]+)$#', '$2', $tmpPath );
        }

        /**
         * @param string $type
         * @return bool
         */
        public function checkType( $type ){
            return in_array( $type, array('image/jpeg','image/png'));
        }
    }