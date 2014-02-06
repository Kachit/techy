<?php
    namespace Techy\Lib\Core\IO;

    class TestResponse implements \Techy\Lib\Core\I\IResponse {

        /**
         * @param string $name
         * @param null $value
         * @param null $expire
         * @param null $secure
         */
        public function cookie( $name, $value = null, $expire = null, $secure = null ){}

        /**
         * @param $content
         * @param $replace
         * @param $code
         */
        public function header( $content, $replace = null, $code = null ){}
    }
