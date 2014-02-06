<?php
    namespace Techy\Lib\Core\Data\Field;

    use Techy\Lib\Core\Data\AbstractField;

    class FieldInt extends AbstractField {
        /**
         * @param $value
         * @return int|null
         */
        public function set( $value ){
            if( is_bool( $value ))
                return $value ? 1 : 0;

            if( is_numeric( $value ))
                return intval( $value );

            return $this->default;
        }
    }
