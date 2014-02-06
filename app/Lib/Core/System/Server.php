<?php
    namespace Techy\Lib\Core\System;

    class Server{
        const SECRET_KEY = 'r!cxB19)*gs$5g8_+ZW74AGLJoy4z';
        const CYPHER = MCRYPT_RIJNDAEL_128;
        const MODE = MCRYPT_MODE_ECB;

        const FLASH_CYPHER = MCRYPT_DES;
        const FLASH_MODE = MCRYPT_MODE_ECB;

        /**
         * @static
         * @param $name
         * @return bool
         */
        public static function param( $name ){
            $s = mb_strtoupper( $name );
            $value = array_key_exists( $s, $_SERVER ) ? $_SERVER[ $s ] : false;
            return $value;
        }

        /**
         * @static
         * @return string
         */
        public static function generateCookie(){
            $sHash = md5( mt_rand( 1, 1234567890 ) . microtime( true ) . mt_rand( 1, 1234567890 ) );
            $sHost = self::param( 'HTTP_HOST' );
            return self::cryptHex( $sHash . '|' . $sHost, false );
        }

        public static function parseCookie( $sHash ){
            $s = self::decryptHex( $sHash );
            if( $s ){
                list( $hash, $host ) = explode( '|', $s );
                return array(
                    'hash' => $hash,
                    'host' => $host
                );
            }
            return false;
        }

        /**
         * Хэш соединения
         *
         * @static
         * @return string
         */
        public static function getIpStamp(){
            $sKey =
                Server::param( 'HTTP_X_FORWARDED_FOR' ) .
                Server::param( 'HTTP_VIA' ) .
                Server::param( 'HTTP_VIA' ) .
                Server::param( 'HTTP_PROXY' ) .
                Server::param( 'REMOTE_ADDR' ) .
                Server::param( 'HTTP_PROXY' ) .
                Server::param( 'HTTP_PROXY_CONNECTION' ) .
                Server::param( 'HTTP_PROXY_CONNECTION' ) .
                Server::param( 'HTTP_X_COMING_FROM' ) .
                Server::param( 'HTTP_X_COMING_FROM' );
            return md5( $sKey );
        }

        /**
         * @static
         * @param $text
         * @param bool $base64
         * @param string $secretKey
         * @return string
         */
        public static function crypt( $text, $base64 = true, $secretKey = self::SECRET_KEY ){
            $iv = \mcrypt_create_iv( \mcrypt_get_iv_size( self::CYPHER, self::MODE ), MCRYPT_RAND );
            $text = \mcrypt_encrypt( self::CYPHER, $secretKey, $text, self::MODE, $iv );
            return $base64 ? base64_encode( $text ) : $text;
        }

        /**
         * @static
         * @param $text
         * @param bool $base64
         * @param string $secretKey
         * @return mixed
         */
        public static function decrypt( $text, $base64 = true, $secretKey = self::SECRET_KEY ){
            $iv = \mcrypt_create_iv( \mcrypt_get_iv_size( self::CYPHER, self::MODE ), MCRYPT_RAND );
            $text = $base64 ? base64_decode( $text ) : $text;
            return str_replace( "\x0", '', \mcrypt_decrypt( self::CYPHER, $secretKey, $text, self::MODE, $iv ) );
        }

        public static function decryptHex( $get ){
            $len = strlen( $get );
            $res = '';
            for( $i = 0; $i < $len; $i += 2 ){
                $c = $get[ $i ] . $get[ $i + 1 ];
                $res .= chr( hexdec( $c ) );
            }
            return self::decrypt( $res, false );
        }

        public static function cryptHex( $sUrl, $useSep = false ){
            $sCrypted = self::crypt( $sUrl, false );
            $len = strlen( $sCrypted );
            $k = 0;
            $sRes = '';
            for( $i = 0; $i < $len; $i++ ){
                $k++;
                $c = dechex( ord( $sCrypted[ $i ] ) );
                if( strlen( $c ) < 2 )
                    $c = '0' . $c;
                $sRes .= $c;
                if( $k != $len ){
                    if( $useSep ){
                        if( ( $k % 8 ) == 0 )
                            $sRes .= '/';
                    }
                }
            }
            return $sRes;
        }

        public static function isCli() {
            return php_sapi_name() == 'cli';
        }

        /**
         * @param bool $strong
         * @return string
         */
        public static function createGuid($strong = false){
            $uid = uniqid('', true) . static::getIpStamp();
            if ($strong) {
                return hash('sha512',  $uid);
            }
            return hash('ripemd128',  $uid);
        }
    }
