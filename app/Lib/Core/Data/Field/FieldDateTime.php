<?php
    namespace Techy\Lib\Core\Data\Field;

    use Techy\Lib\Core\Data\AbstractField;

    class FieldDateTime extends AbstractField {
        /**
         * @param $value
         * @return int|null
         */
        public function set( $value ){
            if( is_numeric( $value ))
                return intval( $value );

            if( is_string( $value ))
                return strtotime( $value );

            return $this->default;
        }
    }
