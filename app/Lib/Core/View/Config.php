<?php
    namespace Techy\Lib\Core\View;

	use Techy\Lib\Core\System\Application;

    abstract class Config {

        const
            VERSIONS_CONFIG = 'versions',
            JS_CONFIG = 'js',
            LOCALES_CONFIG = 'locales'
        ;

        /**
         * @return array
         */
        protected static function getJs(){
            return array();
        }

        /**
         * @return array
         */
        protected static function getStyles(){
            return array();
        }

        /**
         * @static
         * @return string
         */
        public static function styles(){
            $html = '';
            $styles = static::getStyles();
            if(!empty( $styles['less'] )){
                foreach( $styles['less'] as $path => $version ){
                    $html .= '<link rel="stylesheet/less" href="/'. $path .'?'. $version .'" />';
                }
            }
            if(!empty( $styles['css'] )){
                foreach( $styles['css'] as $path => $version ){
                    $html .= '<link media="screen" href="/'. $path .'?'. $version .'" type="text/css" rel="stylesheet" />';
                }
            }
            return $html;
        }

        /**
         * @static
         * @return string
         */
        public static function js(){
            $html = '';
            $scripts = static::getJs();
            foreach( $scripts as $path => $version )
                $html .= '<script type="text/javascript" src="/'. $path .'?'. $version .'"></script>';
            return $html;
        }

        /**
         * @static
         * @param $globals
         * @return string
         */
        public static function globals( $globals ){
            if (!$globals) {
                $globals = [];
            }
            $html = '<script type="text/javascript">window.GLOBALS = '. json_encode( $globals ) .';</script>';
            return $html;
        }
	}