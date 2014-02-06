<?php
    namespace Techy\Lib\Utils;

    class ImageConverter {

        protected $filename;

        public function __construct( $filename ){
            $this->filename = $filename;
        }

        /**
         * @param string $toFile
         * @param int $width
         * @param int $height
         * @param bool $crop
         */
        public function resample( $toFile, $width, $height, $crop = false ){
            $Image = new \Imagick( $this->filename );
            if( $crop ){
                $Image->cropThumbnailImage( $width, $height );
            }
            else{
                $Image->thumbnailImage( $width, $height, true );
            }
            $Image->setImageFormat( 'jpeg' );
            $Image->setCompressionQuality( 90 );
            $Image->writeImage( $toFile );
            $Image->destroy();
        }
    }