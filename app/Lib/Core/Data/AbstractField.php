<?php
    namespace Techy\Lib\Core\Data;

	abstract class AbstractField {

        /**
         * TYPES
         */
        const
            FIELD_ARRAY = 'Array',
            FIELD_BOOLEAN = 'Boolean',
            FIELD_DATE_TIME = 'DateTime',
            FIELD_DECIMAL = 'Decimal',
            FIELD_ENUMERABLE = 'Enum',
            FIELD_INT = 'Int',
            FIELD_OBJECT = 'Object',
            FIELD_POINT = 'Point',
            FIELD_SET = 'Set',
            FIELD_STRING = 'String',
            FIELD_VECTOR = 'Vector'
        ;

        protected $default = null;

        /**
         * create only by factory
         */
        private function __construct(){}

        /**
         * @param string $option
         * @param mixed $value
         * @return bool
         */
        final private function setOption( $option, $value = null ){
            $method = 'set'. ucfirst( $option );
            if(!method_exists( $this, $method ))
                return false;

            $this->$method( $value );
            return true;
        }

        private function setDefault( $value ){
            $this->default = $value;
            return $this;
        }

        /**
         * @param $value
         * @return mixed
         */
        public function set( $value ){
            return is_null( $value ) ? $this->default : $value;
        }

        /**
         * @static
         * @param $type
         * @param array $options
         * @return \Techy\Lib\Core\Data\AbstractField
         * @throws \UnexpectedValueException
         */
        final public static function create( $type, array $options = array()){
            $className = __NAMESPACE__.'\\Field\\Field'. $type;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Class '. $className .' not implemented' );

            /**
             * @var AbstractField $field
             */
            $field = new $className();
            if(!( $field instanceof AbstractField ))
                throw new \UnexpectedValueException( 'Class '. $className .' is not an AbstractField' );

            foreach( $options as $option => $value ){
                if(!$field->setOption( $option, $value ))
                    throw new \UnexpectedValueException( 'Option setter for '. $type .' "'. $option .'" is not implemented' );
            }
            return $field;
        }
    }
