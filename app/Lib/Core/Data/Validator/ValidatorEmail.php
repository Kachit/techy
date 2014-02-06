<?php
    namespace Techy\Lib\Core\Data\Validator;

    use Techy\Lib\Core\Data\AbstractValidator;

    class ValidatorEmail extends AbstractValidator {

        /**
         * @param $value
         * @return bool
         */
        public function validate( $value ){
            return !!filter_var( $value, FILTER_VALIDATE_EMAIL );
        }
    }
