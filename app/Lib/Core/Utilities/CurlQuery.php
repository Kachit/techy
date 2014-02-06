<?php
    namespace Techy\Lib\Core\Utilities;

    use Techy\Lib\Core\Exception\CurlError;

    /**
     * Class CurlQuery
     * Implements chain pattern
     *
     * @package Techy\Lib\Core\Utilities
     */
    class CurlQuery {

        protected
            $_url,
            $_get = array(),
            $_post = array(),
            $_options = array(),
            $_headers,
            $_response,
            $_info,
            $_error;

        public function __construct( $url ){
            $this->_url = $url;
        }

        public function get( array $get ){
            $this->_get = $get;
            return $this;
        }

        public function post( array $post ){
            $this->_post = $post;
            return $this;
        }

        public function options( array $options ){
            $this->_options = $options;
            return $this;
        }

        public function query(){
            $options = $this->_options + array(
                CURLOPT_URL => rtrim( $this->_url, '?' ) . ( $this->_get ? '?'. http_build_query( $this->_get ) : '' ),
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FORBID_REUSE => 1,
                CURLOPT_TIMEOUT => 20,
            );
            $options[CURLOPT_HEADER] = 1;
            if( $this->_post ){
                $options[CURLOPT_POST] = 1;
                $options[CURLOPT_POSTFIELDS] = http_build_query( $this->_post );
            }
            $ch = curl_init();
            curl_setopt_array( $ch, $options );
            $response = curl_exec( $ch );
            $info = curl_getinfo( $ch );
            if(!$response || !$info )
                throw new CurlError();
            curl_close( $ch );

            list( $this->_headers, $this->_response ) = explode( "\r\n\r\n", $response, 2 );
            $this->_info = $info;

            return $this;
        }

        public function headers(){
            return $this->_headers;
        }

        public function response(){
            return $this->_response;
        }

        public function info( $option = null ){
            if( $option )
                return isset( $this->_info[$option] ) ? $this->_info[$option] : null;

            return $this->_info;
        }
    }