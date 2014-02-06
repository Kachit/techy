<?php

    namespace Techy\Lib\Core\Pdf;

    class Wkhtmltopdf extends PdfCreator {

        protected $html = null;
        protected $tmpFile = null;

        private $converter = null;

        public $debug = false;

        public function create(){
            $pdf = $this->render();
            $tmpName = tempnam( sys_get_temp_dir(), 'pdf_' ) . '.pdf';
            file_put_contents( $tmpName, $pdf );

            return $tmpName;
        }

        public function render(){
            $exec = "{$this->getConverter()} {$this->url} -";
            if( $this->debug )
                $exec .= ' 2>&1';
            else
                $exec .= ' 2> /dev/null';
            $pdf = shell_exec( $exec );
            $this->deleteTemp();
            return $pdf;
        }

        public function setConverter( $converterPath ){
            $c = exec( "which $converterPath 2> /dev/null" );
            if( is_executable( $c ))
                $this->converter = $c;
        }

        public function getConverter(){
            if( !$this->converter || !is_executable( $this->converter )){
                $c = exec( 'which wkhtmltopdf 2> /dev/null ' );
                if( !is_executable( $c )){
                    throw new \InvalidArgumentException( 'Cann\'t execute converter' );
                    return false;
                }
                $this->converter = $c;
            }

            return $this->converter;
        }

    }