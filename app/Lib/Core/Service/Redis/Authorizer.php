<?php
    namespace Techy\Lib\Core\Service\Redis;

	use Techy\Lib\Core\System\Application;

    class Authorizer extends Base implements \Techy\Lib\Core\Service\I\IAuthorizer {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId .'_a_'. $name .'_';
        }

        public function set( $key, array $data ){
            try {
                $this->client()->set( $this->name . $key, serialize( $data ));
                return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t set data for '. $this->name . $key );
                return false;
            }
        }

        public function get( $key ){
            try {
                $val = $this->client()->get( $this->name . $key );
                if( $val )
                    return unserialize( $val );
                else
                    return false;
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
