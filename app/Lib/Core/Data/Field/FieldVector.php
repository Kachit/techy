<?php
    namespace Techy\Lib\Core\Data\Field;

    use Techy\Lib\Core\Data\AbstractField;

    class FieldVector extends AbstractField {

        /**
         * @param $value
         * @return array|null
         */
        public function set( $value ){
            if( is_string( $value )){
                $value = trim( $value, '()' );
                $value = $value ? explode( ',', $value ) : array();
            }

            if(!is_array( $value ))
                return $this->default;

            foreach( $value as &$v ){
                $v = trim( $v, '"' );
                if( is_numeric( $v ))
                    $v = intval( $v );
            }

            return array_unique( $value );
        }
    }
