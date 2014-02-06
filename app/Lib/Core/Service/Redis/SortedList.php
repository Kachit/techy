<?php
    namespace Techy\Lib\Core\Service\Redis;

	use Techy\Lib\Core\System\Application;

    class SortedList extends Base {

        protected $seq;
        protected $descending = true;

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId .'_zl_'. $name .'_';
            $this->seq = $spotId .'_zls_'. $name .'_';
        }

        public function setDescending( $desc = true ){
            $this->descending = $desc;
        }

        public function append( $key, $member ){
            try {
                $idx = $this->client()->incr( $this->seq . $key );
                if(!$idx )
                    return false;
                $idx = intval( $idx );

                if( false !== $this->client()->zadd( $this->name . $key, $idx, $member ))
                    return $idx;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
            }
            return false;
        }

        public function get( $key, $idx ){
            try {
                $args = array( $this->name . $key, $idx, $idx, array( 'limit' => array( 0, 1 )));
                $val = call_user_func_array( array( $this->client(), 'zrangebyscore' ), $args );
                if( $val )
                    return reset( $val );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
            }
            return false;
        }

        public function exists( $key, $member ){
            return $this->client()->zscore( $this->name . $key, $member ) ? true : false;
        }

        public function update( $key, $idx, $member ){
            try {
                if( $this->client()->zadd( $this->name . $key, $idx, $member ))
                    return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
            }
            return false;
        }

        public function range( $key, $fromIdx, $count = null ){
            try {
                $method = $this->descending ? 'zrevrangebyscore' : 'zrangebyscore';
                if( $this->descending )
                    $args = array( $this->name . $key, $fromIdx ?: '+inf', '-inf' );
                else
                    $args = array( $this->name . $key, $fromIdx ?: '-inf', '+inf' );
                if( $count )
                    $args[] = array( 'withscores' => 1, 'limit' => array( 0, $count ));

                $bulk = call_user_func_array( array( $this->client(), $method ), $args ) ?: array();
                if(!$count )
                    return $bulk;

                $result = array();
                foreach( $bulk as $item )
                    $result[$item[1]] = $item[0];
                return $result;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function remove( $key, $member ){
            try {
                return $this->client()->zrem( $this->name . $key, $member );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        /**
         * @param $key
         * @return int
         */
        public function count( $key ){
            try {
                return intval( $this->client()->zcard( $this->name . $key ));
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }
    }
