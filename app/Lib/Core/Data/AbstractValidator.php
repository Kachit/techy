<?php
    namespace Techy\Lib\Core\Data;

	abstract class AbstractValidator {

        /**
         * TYPES
         */
        const
            VALIDATOR_NOT_NULL = 'NotNull',
            VALIDATOR_NOT_EMPTY = 'NotEmpty',
            VALIDATOR_EMAIL = 'Email',
            VALIDATOR_MATCH = 'Match',
            VALIDATOR_URL = 'Url',
            VALIDATOR_FIXED_LENGTH = 'FixedLength'
        ;

        /**
         * Overwrite this function for custom validation
         *
         * @param $value
         * @return bool
         */
        public function validate( $value ){
            return !!$value;
        }

        /**
         * Returns error message string
         *
         * @return string
         */
        final public function getMessage(){
            return $this->message;
        }

        /**
         * Factory to create Validators
         *
         * @static
         * @param string $type
         * @param string $message
         * @param array $options
         * @return \Techy\Lib\Core\Data\AbstractValidator
         * @throws \UnexpectedValueException
         */
        final public static function create( $type, $message, array $options = array()){
            $className = __NAMESPACE__.'\\Validator\\Validator'. $type;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Class '. $className .' is not implemented' );

            /**
             * @var AbstractValidator $Validator
             */
            $Validator = new $className( $message );
            if(!( $Validator instanceof AbstractValidator ))
                throw new \UnexpectedValueException( 'Class '. $className .' is not an AbstractValidator' );

            foreach( $options as $option => $value )
                $Validator->setOption( $option, $value );

            return $Validator;
        }

        /**
         * @var string $message
         */
        private $message;

        /**
         * create only by factory
         */
        private function __construct( $message ){
            $this->message = $message;
        }

        /**
         * @param string $option
         * @param mixed $value
         * @throws \UnexpectedValueException
         */
        private function setOption( $option, $value = null ){
            $method = 'set'. ucfirst( $option );
            if(!method_exists( $this, $method ))
                throw new \UnexpectedValueException( 'Option setter for '. get_class( $this ) .' "'. $option .'" is not implemented' );

            $this->$method( $value );
        }
    }
