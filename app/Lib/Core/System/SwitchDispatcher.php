<?php
    namespace Techy\Lib\Core\System;

    use Techy\Lib\Core\I\IDispatcher;

    class SwitchDispatcher implements IDispatcher {

        public function getApplicationName(){
            return $this->name;
        }

        public function setApplicationName( $name ){
            $this->name = $name;
        }

        protected $name;

        /**
         * @var SwitchDispatcher
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return SwitchDispatcher
         */
        public static function instance(){
            if(!static::$Instance )
                static::$Instance = new static();
            return static::$Instance;
        }
    }