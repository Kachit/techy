<?php
    namespace Techy\Lib\Core\Data\Field;

    use Techy\Lib\Core\Data\AbstractField;

    class FieldBoolean extends AbstractField {
        /**
         * @param $value
         * @return bool|null
         */
        public function set( $value ){
            // hack for scalar booleans
            if( is_scalar( $value )){
                if( in_array( strtolower( $value ), array( 'f', 'false', 'no' )))
                    return false;
            }

            if(!is_null( $value ))
                return !!$value;

            return $this->default;
        }
    }
