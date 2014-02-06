<?php
    namespace Techy\Lib\Core\Data\Field;

	use Techy\Lib\Core\Data\AbstractField;

    class FieldString extends AbstractField {
        /**
         * @param $value
         * @return string|null
         */
        public function set( $value ){
            if( is_scalar( $value ) )
                return strval( $value );

            return $this->default;
        }
    }
