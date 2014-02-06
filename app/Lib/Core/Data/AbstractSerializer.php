<?php
    namespace Techy\Lib\Core\Data;

	abstract class AbstractSerializer {

        /**
         * TYPES
         */
        const
            SERIALIZER_COMMON = 'Common',
            SERIALIZER_BOOLEAN = 'Boolean',
            SERIALIZER_FIXED_ARRAY = 'FixedArray',
            SERIALIZER_POINT = 'Point'
        ;

        /**
         * Overwrite this function for custom filtration
         *
         * @param $value
         * @return mixed
         */
        public function serialize( $value ){
            return $value;
        }

        /**
         * Overwrite this function for custom filtration
         *
         * @param $value
         * @return mixed
         */
        public function unserialize( $value ){
            return $value;
        }

        /**
         * Factory to create Validators
         *
         * @static
         * @param string $type
         * @param array $options
         * @return \Techy\Lib\Core\Data\AbstractSerializer
         * @throws \UnexpectedValueException
         */
        final public static function create( $type, array $options = array()){
            $className = __NAMESPACE__.'\\Serializer\\Serializer'. $type;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Class '. $className .' is not implemented' );

            /**
             * @var AbstractSerializer $Serializer
             */
            $Serializer = new $className();
            if(!( $Serializer instanceof AbstractSerializer ))
                throw new \UnexpectedValueException( 'Class '. $className .' is not an AbstractSerializer' );

            foreach( $options as $option => $value )
                $Serializer->setOption( $option, $value );

            return $Serializer;
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
