<?php
    namespace Techy;

    /**
     * Main class for this framework
     */
    final class Autoloader {
        const
            CONFIG_NAMESPACE = 'Config',

            PLATFORM_DEV = 'dev',
            PLATFORM_TEST = 'test',
            PLATFORM_PROD = 'prod',
            PLATFORM_PREPROD = 'preprod';

        private static
            $platforms = array(
                self::PLATFORM_DEV => 1,
                self::PLATFORM_TEST => 2,
                self::PLATFORM_PROD => 3,
                self::PLATFORM_PREPROD => 4,
            ),
            $shortcuts = array();

        /**
         * @var string
         */
        private static $platform;
        /**
         * @static
         * @return string
         */
        public static function platfotm(){
            return self::$platform;
        }

        /**
         * @static
         * @param string $platform
         * @throws \UnexpectedValueException
         * @throws \ErrorException
         */
        public static function register( $platform = 'dev' ){
            if(!array_key_exists( $platform, self::$platforms ))
                throw new \UnexpectedValueException( 'Platform '. $platform .' does not exist' );
            self::$platform = $platform .'/';

            if(!\spl_autoload_register(__NAMESPACE__.'\\Autoloader::load'))
                throw new \ErrorException( 'Could not register '.__NAMESPACE__.'\'s class config autoload function' );
        }

        public static function load( $fullClassName ){
            $namespaces = explode( '\\', $fullClassName );
            $className = array_pop( $namespaces );
            $ns = array_shift( $namespaces );
            $alias = false;
            if( $ns != __NAMESPACE__ ){
                if(!$namespaces && array_key_exists( $ns, self::$shortcuts ) ){
                    $namespaces = explode( '\\', self::$shortcuts[$ns] );
                    $alias = true;
                }
                else
                    return;
            }

            $namespace = implode( '/', $namespaces );
            switch( reset( $namespaces )){
                case self::CONFIG_NAMESPACE:
                    $load = CONFIG_CLASSES_DIR . $className .'.php';
                    break;

                default:
                    $load = APP_DIR . ( $namespace ? $namespace .'/' : '' ) . $className .'.php';
            }

            if(!file_exists( $load ))
                throw new \UnexpectedValueException( 'Could not load library for class '. $fullClassName .' - '. $load . ' - '. $namespace );

            require_once $load;

            if( $alias )
                class_alias( __NAMESPACE__ .'\\'. implode( '\\', $namespaces ) .'\\'. $className, $fullClassName );
        }
    }