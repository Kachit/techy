<?php
    namespace Techy\Lib\Core\Data\Validator;

    use Techy\Lib\Core\Data\AbstractValidator;

    class ValidatorNotNull extends AbstractValidator {

        /**
         * @param $value
         * @return bool
         */
        public function validate( $value ){
            return !is_null( $value );
        }
    }
