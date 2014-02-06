<?php
    namespace Techy\Lib\Core\Service\Redis;

	use Techy\Lib\Core\System\Application;

    class HashAuthorizer extends Authorizer implements \Techy\Lib\Core\Service\I\IHashAuthorizer {

        public function set( $key, array $data ){
            try {
                $args = array( $this->name . $key );
                foreach( $data as $field => $value ){
                    array_push( $args, $field );
                    array_push( $args, $value );
                }
                return call_user_func_array( array( $this->client(), 'hmset' ), $args );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t set data for '. $this->name . $key );
                return false;
            }
        }

        public function setField( $key, $field, $value ){
            try {
                return $this->client()->hset( $this->name . $key, $field, $value );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t set data for '. $this->name . $key );
                return false;
            }
        }

        public function incrField( $key, $field, $value ){
            try {
                return $this->client()->hincrby( $this->name . $key, $field, $value );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t set data for '. $this->name . $key );
                return false;
            }
        }

        public function get( $key ){
            try {
                $val = $this->client()->hgetall( $this->name . $key );
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

        public function getField( $key, $field ){
            try {
                return $this->client()->hget( $this->name . $key, $field );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function removeField( $key, $field ){
            try {
                $args = func_get_args();
                $args[0] = $this->name . $key;
                call_user_func_array( array( $this->client(), 'hdel' ), $args );
                return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }
    }
