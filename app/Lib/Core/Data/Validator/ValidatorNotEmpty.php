<?php
    namespace Techy\Lib\Core\Data\Validator;

    use Techy\Lib\Core\Data\AbstractValidator;

    class ValidatorNotEmpty extends AbstractValidator {

        /**
         * @param $value
         * @return bool
         */
        public function validate( $value ){
            return !empty( $value );
        }
    }
