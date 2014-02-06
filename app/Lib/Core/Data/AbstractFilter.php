<?php
    namespace Techy\Lib\Core\Data;

	abstract class AbstractFilter {

        /**
         * TYPES
         */
        const
            FILTER_DIGITS = 'Digits',
            FILTER_URL = 'Url',
            FILTER_TRIM = 'Trim',
            FILTER_LOWERCASE_TRIM = 'LowercaseTrim'
        ;

        /**
         * Overwrite this function for custom filtration
         *
         * @param $value
         * @return mixed
         */
        public function filter( $value ){
            return $value;
        }

        /**
         * Factory to create Validators
         *
         * @static
         * @param string $type
         * @param array $options
         * @return \Techy\Lib\Core\Data\AbstractFilter
         * @throws \UnexpectedValueException
         */
        final public static function create( $type, array $options = array()){
            $className = __NAMESPACE__.'\\Filter\\Filter'. $type;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Class '. $className .' is not implemented' );

            /**
             * @var AbstractFilter $Filter
             */
            $Filter = new $className();
            if(!( $Filter instanceof AbstractFilter ))
                throw new \UnexpectedValueException( 'Class '. $className .' is not an AbstractFilter' );

            foreach( $options as $option => $value )
                $Filter->setOption( $option, $value );

            return $Filter;
        }

        /**
         * create only by factory
         */
        private function __construct(){}

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
