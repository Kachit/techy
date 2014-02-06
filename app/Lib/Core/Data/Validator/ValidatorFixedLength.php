<?php
    namespace Techy\Lib\Core\Data\Validator;

    use Techy\Lib\Core\Data\AbstractValidator;

    class ValidatorFixedLength extends AbstractValidator {

        private $length;

        protected function setLength( $length ){
            $this->length = $length;
        }

        /**
         * @param $value
         * @return bool
         */
        public function validate( $value ){
            return $this->length === mb_strlen( $value );
        }
    }
