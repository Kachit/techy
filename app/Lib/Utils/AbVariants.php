<?php
    namespace Techy\Lib\Utils;

    use Techy\Lib\Core\System\Application;

    class AbVariants {

        protected static $variants = array(
            1,
            2,
            3,
            4,
            5,
            6,
            7,
        );

        /**
         * @var AbVariants
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return AbVariants
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        private $variant;

        public function get(){
            if(!$this->variant ){
                $Application = Application::instance();
                $this->variant = $Application->getRequest()->getCookie('h_var');
                if(!$this->variant ){
                    $this->variant = self::$variants[array_rand( self::$variants )];
                    $Application->getResponse()->cookie('h_var', $this->variant );
                }
            }
            return $this->variant;
        }
    }
