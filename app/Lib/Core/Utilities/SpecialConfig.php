<?php
    namespace Techy\Lib\Core\Utilities;

    use Techy\Lib\Core\I\ISpecialConfig;

    class SpecialConfig implements ISpecialConfig {

        protected $values;

        public function __construct( $path ){
            $DynamicConfigFile = DynamicConfigFile::instance();
            $this->values = $DynamicConfigFile->read( 'special', $path );
        }

        public function get( $offset ){
            if(!array_key_exists( $offset, $this->values ))
                return null;

            return $this->values[$offset];
        }

        public function set( $offset, $value ){
            $this->values[$offset] = $value;
        }


        public function offsetExists( $offset ){
            return isset( $this->values[$offset] );
        }

        public function offsetGet( $offset ){
            return $this->get( $offset );
        }

        public function offsetSet( $offset, $value ){
            $this->set( $offset, $value );
        }

        public function offsetUnset( $offset ){
            if( isset( $this->values[$offset] ))
                unset( $this->values[$offset] );
        }
    }