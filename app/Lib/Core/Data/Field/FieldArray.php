<?php
    namespace Techy\Lib\Core\Data\Field;

	use Techy\Lib\Core\Data\AbstractField;

    class FieldArray extends AbstractField {

        /**
         * @param $value
         * @return array|null
         */
        public function set( $value ){
            if( is_object( $value ))
                $value = (array)$value;

            if( is_string( $value ))
                $value = unserialize( $value );

            if( is_array( $value ))
                return $value;

            return $this->default;
        }
    }
