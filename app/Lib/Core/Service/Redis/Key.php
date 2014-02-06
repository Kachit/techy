<?php
    namespace Techy\Lib\Core\Service\Redis;

	use Techy\Lib\Core\System\Application;

    class Key extends Base {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId .'_k_'. $name .'_';
        }

        public function set( $key, $value ){
            try {
                $this->client()->set( $this->name . $key, $value );
                return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function get( $key ){
            try {
                $val = $this->client()->get( $this->name . $key );
                if( $val )
                    return $val;
                else
                    return false;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function exists( $key ){
            try {
                return $this->client()->exists( $this->name . $key );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function remove( $key ){
            try {
                $this->client()->del( $this->name . $key );
                return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }
    }
