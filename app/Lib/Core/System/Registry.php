<?php
    namespace Techy\Lib\Core\System;

    final class Registry {
        /**
         * @var \Techy\Lib\Core\I\IRegistry
         */
        private static $Instance;

        private $Config;

        private function __construct(){}

        /**
         * @static
         * @return \Techy\Lib\Core\I\IRegistry
         */
        public static function instance(){
            if(!static::$Instance){
                static::$Instance = new self();
            }
            return static::$Instance;
        }

        /**
         * @param RegistryConfig $Config
         */
        public function initialize( RegistryConfig $Config ){
            $this->Config = $Config;
        }

        public function __call( $name, $arguments ){
            if(!method_exists( $this->Config, $name ))
                throw new \UnexpectedValueException( 'Class for '. $name .' is not registered in '. __CLASS__ );

            try {
                return $this->Config->$name();
            }
            catch( \Exception $E ){
                throw new \UnexpectedValueException( $E->getMessage());
            }
        }
    }