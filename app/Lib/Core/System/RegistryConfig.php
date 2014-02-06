<?php
    namespace Techy\Lib\Core\System;

    abstract class RegistryConfig implements \Techy\Lib\Core\I\IRegistry {
        /**
         * @var RegistryConfig $Instance
         */
        protected static $Instance;

        final protected function __construct(){}

        /**
         * @static
         * @return RegistryConfig
         */
        final public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }
    }