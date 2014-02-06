<?php
    namespace Techy\Lib\Core\Data\Field;

    class FieldSet extends FieldEnum {

        /**
         * @param $value
         * @return array|null
         */
        public function set( $value ){
            if( is_string( $value )){
                $value = trim( $value, '{}' );
                $value = $value ? explode( ',', $value ) : array();
            }

            if(!is_array( $value ))
                return $this->default;

            foreach( $value as $k => &$v ){
                $v = trim( $v, '"' );
                if( in_array( $v, $this->types )){
                    if( is_numeric( $v ))
                        $v = intval( $v );
                }else{
                    unset( $value[$k] );
                }
            }

            return array_unique( $value );
        }
    }
