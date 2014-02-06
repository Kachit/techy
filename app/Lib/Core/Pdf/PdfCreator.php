<?php

    namespace Techy\Lib\Core\Pdf;

    abstract class PdfCreator {

        protected $Config;
        protected $tmpFile;
        protected $url;

        private function __construct( Config $Config ){
            $this->Config = $Config;
        }

        abstract public function create();
        abstract public function render();

        final public static function get( Config $Config ){
            $className = __NAMESPACE__ .'\\'. ucfirst( $Config->type );
            if(!class_exists( $className, true ))
                throw new \UnexpectedValueException( $className .'" is not implemented' );

            return new $className( $Config );
        }

        public function byHtml( $html ){
            $this->tmpFile = $this->createTmpFile( $html );
            $this->url = 'file://' . $this->tmpFile;
            chmod( $this->tmpFile, 0664 );
        }

        public function byUrl( $url ){
            $this->url = $url;
        }

        private function createTmpFile( $data, $ext = 'html' ){
            $tmpName = tempnam( sys_get_temp_dir(), 'wkhtml_' ) . '.' . $ext;
            if( $data !== null )
                file_put_contents( $tmpName, $data );

            return $tmpName;
        }

        protected function deleteTemp(){
            if( $this->tmpFile !== null ){
                if( !unlink( $this->tmpFile ) ){
                    return false;
                }
            }
            return true;
        }
    }