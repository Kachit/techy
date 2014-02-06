<?php
    namespace Techy\Lib\Core\Data\Field;

	use Techy\Lib\Core\Data\AbstractField;

    class FieldEnum extends AbstractField {
        protected $types;

        /**
         * @param $value
         * @return mixed
         */
        public function set( $value ){
            if( is_numeric( $value ))
                $value = intval( $value );

            if( in_array( $value, $this->types ))
                return $value;

            return $this->default;
        }

        public function setTypes( array $value ){
            $this->types = $value;
        }
    }
