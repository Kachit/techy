<?php
    namespace Techy\Lib\Core\Data\Field;

	use Techy\Lib\Core\Data\AbstractField;

    class FieldDecimal extends AbstractField {
        private $precision;

        /**
         * @param $value
         * @return float|null
         */
        public function set( $value ){
            if( is_numeric( $value )){
                $value = floatval( $value );
                return $this->precision ? round( $value, $this->precision ) : $value;
            }

            return $this->default;
        }

        public function setPrecision( $value ){
            $this->precision = $value;
        }
    }
