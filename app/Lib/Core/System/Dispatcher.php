<?php
    namespace Techy\Lib\Core\System;

    use Techy\Lib\Core\I\IDispatcher;

    class Dispatcher implements IDispatcher {

        /**
         * @return string
         */
        public function getApplicationName(){
            return @$_SERVER['APPLICATION_NAME'] ?: 'Admin';
        }

        /**
         * @var \Techy\Lib\Core\I\IDispatcher
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return \Techy\Lib\Core\I\IDispatcher
         */
        public static function instance(){
            if(!static::$Instance )
                static::$Instance = new static();
            return static::$Instance;
        }
    }