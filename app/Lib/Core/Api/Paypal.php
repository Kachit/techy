<?php
	namespace Techy\Lib\Core\Api;

    use Techy\Lib\Core\Utilities\CurlQuery;

    /**
     * Class Paypal
     * Implements calls to paypal api
     *
     * @package Techy\Lib\Core\Api
     * @version 93
     */
    class Paypal {

        private function deformatNVP( $nvpstr ){
            $intial = 0;
            $nvpArray = array();

            while( strlen( $nvpstr )){
                // position of Key
                $keypos= strpos($nvpstr,'=');
                // position of Value
                $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
                // getting the Key and Value values and storing in a Associative Array
                $keyval = substr($nvpstr,$intial,$keypos);
                $valval = substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
                // decoding the respose
                $nvpArray[urldecode($keyval)] = urldecode( $valval);
                $nvpstr = substr($nvpstr,$valuepos+1,strlen($nvpstr));
            }
            $nvpArray['ACK'] = empty( $nvpArray['ACK'] ) ? '' : strtoupper( $nvpArray['ACK'] );
            return $nvpArray;
        }

        public function webscr( array $params ){
            return PAYPAL_API_WEBSCR_URL . http_build_query( $params );
        }

        public function call( $postData ){
            $CurlQuery = new CurlQuery( PAYPAL_API_NVP_URL );
            $response = $CurlQuery
                ->post(
                    array(
                        'SIGNATURE' => PAYPAL_API_SIGNATURE,
                        'USER' => PAYPAL_API_USERNAME,
                        'PWD' => PAYPAL_API_PASSWORD,
                        'VERSION' => 93,
                    ) + $postData
                )
                ->options( array(
                    CURLOPT_SSL_VERIFYPEER => 1,
                    CURLOPT_SSL_VERIFYHOST => 2,
//                    CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', // wtf cert?
                ))
                ->query()
                ->response();

            return $this->deformatNVP( $response );
        }
    }
