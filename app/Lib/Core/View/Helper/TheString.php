<?php
    namespace Techy\Lib\Core\View\Helper;

    class TheString {

        public static function shorten( $string, $length ){
            if( mb_strlen( $string ) > $length )
                $string = mb_substr( $string, 0, $length ) .'...';

            return $string;
        }
    }