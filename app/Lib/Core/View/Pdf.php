<?php

    namespace Techy\Lib\Core\View;

    require_once EXTERNAL_DIR . 'snappy/src/autoload.php';

    class Pdf extends Native {

        public function render( $layoutName = false ){
            $html = parent::render( $layoutName );
            $Snappy = new \Knp\Snappy\Pdf( 'xvfb-run --server-args="-screen 0, 1024x768x24" /usr/bin/wkhtmltopdf' );

            $pdf = $Snappy->getOutputFromHtml( $html );
            header( 'Content-type: application/pdf' );
            header( 'Content-Disposition: attachment; filename="Счёт.pdf"' );
            return $pdf;

//            $filename = tempnam( sys_get_temp_dir(), 'html_' ) . '.html';
//            file_put_contents( $filename, $html );
//            $Pdf = \Techy\Lib\Helper\Pdf::instance();
//            $Pdf->setConverter( 'xvfb-run --server-args="-screen 0, 1024x768x24" /usr/bin/wkhtmltopdf' );
////            $tmpPath = tempnam( \sys_get_temp_dir(), 'pdf_' ) . '.pdf';
//            $tmpPath = '-';
//            $pdf = $Pdf->convert( $filename, $tmpPath );
////            header( 'Content-type: application/pdf' );
////            header( 'Content-Disposition: attachment; filename="Счёт.pdf"' );
////            readfile( $tmpPath );
//            echo $pdf;
//            return true;
        }
    }