<?php
    namespace Techy\Lib\Core\Service\Redis;

	use Techy\Lib\Core\System\Application;

    class DelayedQueue extends Base implements \Techy\Lib\Core\Service\I\IQueue {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId .'_queue_'. $name;
        }

        public function push( $data, $options = array()){
            try {
                $this->client()->zadd( $this->name, $options['timer'], $data );
                return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function pushStack( $item ){
            try {
                $args = func_get_args();
                foreach( $args as $item )
                    $this->client()->zadd( $this->name, $item['options']['timer'], $item['data'] );
                return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function pull( $count = 1 ){
            try {
                $now = \Techy\Lib\Core\System\Registry::instance()->Date()->timestamp();
                $range = $this->client()->zrangebyscore( $this->name, 1, $now, array( 'limit' => array( 0, $count ))) ?: array();
                if( $range ){
                    $args = $range;
                    array_unshift( $args, $this->name );
                    call_user_func_array( array( $this->client(), 'zrem' ), $args );
                }
                return $range;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function len(){
            try {
                return $this->client()->zcount( $this->name, '-inf', '+inf' );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }
    }
