<?php
    namespace Techy\Lib\Core\I;

    interface IRequest {

        /**
         * @abstract
         * @param string|null $var
         * @return mixed
         */
        public function getCookie( $var = null );

        /**
         * @abstract
         * @param string|null $var
         * @return mixed
         */
        public function getGetParams( $var = null );

        /**
         * @abstract
         * @param string|null $var
         * @return mixed
         */
        public function getPostParams( $var = null );

        /**
         * @abstract
         * @param string|null $var
         * @return mixed
         */
        public function getServerVar( $var = null );
    }
	