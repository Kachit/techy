<?php
    namespace Techy\Lib\Core\Data\Validator;

    use Techy\Lib\Core\Data\AbstractValidator;

    class ValidatorMatch extends AbstractValidator {

        private $regex;

        protected function setRegex( $regex ){
            $this->regex = $regex;
        }

        /**
         * @param $value
         * @return bool
         */
        public function validate( $value ){
            return preg_match( $this->regex, $value );
        }
    }
