<?php
    namespace Techy\Lib\Core\Utilities;

    use Techy\Lib\Core\System\Application;

    class I18n implements I\II18n {
        /**
         * @var array
         */
        protected static $Instances;

        protected $locale;

        protected $cache = array();

        protected function __construct( $locale ){
            $this->locale =  $locale;
        }

        /**
         * @static
         * @param string $locale
         * @return I18n
         */
        public static function instance( $locale ){
            if(!isset( static::$Instances[$locale] )){
                static::$Instances[$locale] = new static( $locale );
            }
            return static::$Instances[$locale];
        }

        /**
         * @return string
         * @throws \InvalidArgumentException
         * @throws \UnexpectedValueException
         */
        public function get(){
            $args = func_get_args();
            if(!count( $args ))
                throw new \InvalidArgumentException( __CLASS__.'::'.__METHOD__.' have to pass at least 1 argument: locale variable name ' );
            $string = array_shift( $args );
            $value = $this->_pick( explode( '.', $string ));
            if(!is_string( $value ))
                throw new \UnexpectedValueException( 'Locale variable '. $string .' does not exist' );
            return vsprintf( $value, $args );
        }

        /**
         * @return string
         */
        public function pick(){
            return $this->_pick( func_get_args());
        }

        public function getDir(){
            return Application::instance()->getAppDir() . I18N_DIR . $this->locale;
        }

        public function getCache(){
            return $this->cache;
        }

        protected function _pick( $args ){
            if(!count( $args ))
                throw new \InvalidArgumentException( __CLASS__.'::'.__METHOD__.' have to pass at least 1 argument: loclist name ' );
            $locListName = array_shift( $args );
            if(!isset( $this->cache[$locListName] )){
                include $this->getDir() .'/'. $locListName .'.php';
                $this->cache[$locListName] = $list;
            }
            $value = $this->cache[$locListName];
            $names = array();
            while( null !== $sub = array_shift( $args )){
                $names[] = $sub;
                if(!isset( $value[$sub] ))
                    throw new \UnexpectedValueException( 'Locale value for "'. implode( '.', $names ) .'" does not exist in loclist "'. $locListName .'"' );
                $value = $value[$sub];
            }
            return $value;
        }
    }