<?php
    namespace Techy\Lib\Core\Data\Field;

	use Techy\Lib\Core\Data\AbstractField;

    class FieldPoint extends AbstractField {
        /**
         * @param $value
         * @return array|null
         */
        public function set( $value ){
            if( is_string( $value )){
                if( $value = trim( $value, '()' ))
                    $value = explode( ',' , $value );
            }

            if( is_array( $value )){
                if( 2 === count( $value ) && is_numeric( $value[0] ) && is_numeric( $value[1] ))
                    return $value;
                elseif( isset( $value['x'], $value['y'] ))
                    return array( $value['x'], $value['y'] );
            }

            return $this->default;
        }
    }
