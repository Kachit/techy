<?php
    namespace Techy\Lib\Core\Service\Redis;

	use Techy\Lib\Core\System\Application;

    class SortedSet extends Base {

        protected $counter = null;

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_sorted_set_'. $name .'_';
        }

        public function append( $key, $score , $member ){
            try {
                $args = func_get_args();
                $args[0] = $this->name . $key;
                if( call_user_func_array( array( $this->client(), 'zadd' ), $args )){
                    if( $this->counter !== null )
                        $this->counter += count( $args ) - 1;
                    return true;
                }
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t append data to '. $this->name . $key .' ['. $score .'] ['. $member .']' );
            }
            return false;
        }

        /**
         * @param $key
         * @param $member
         * @return bool
         */
        public function exists( $key, $member ){
            try {
                if( $this->client()->zrank( $this->name . $key , $member ) !== NULL )
                    return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t find data of '. $this->name . $key .' ['. $member .']' );
            }
            return false;
        }

        /**
         * @param $key
         * @param $member
         * @return bool
         */
        public function remove( $key, $member ){
            try {
                $args = func_get_args();
                $args[0] = $this->name . $key;
                if( call_user_func_array( array( $this->client(), 'zrem' ), $args )){
                    if( $this->counter !== null )
                        $this->counter -= count( $args ) - 1;
                    return true;
                }
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t remove data from '. $this->name . $key .' ['. $member .']' );
            }
            return false;
        }

        /**
         * @param $key
         * @return bool
         */
        public function clear( $key ){
            try {
                $this->client()->zremrangebyrank( $this->name . $key , 0, -1 );
                $this->counter = 0;
                return true;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t clear data from '. $this->name . $key );
            }
            return false;
        }

        /**
         * @param $key
         * @param $min
         * @param $max
         * @param $rev
         * @return array
         */
        public function members( $key , $min = '-inf' , $max = '+inf' , $limit = -1 , $rev = false ){
            try {
                if( $rev ){
                    $members = $this->client()->zrevrangebyscore( $this->name . $key , $max , $min , 'withscores' , 'limit' , 0 , $limit );
                }
                else
                    $members = $this->client()->zrangebyscore( $this->name . $key , $min , $max , 'withscores' , 'limit' , 0 , $limit );
                if( $members ){
                    $this->counter = count( $members );
                    return $members;
                }
                return array();
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                Application::instance()->getLogger()->log( 'redis-errors', 'Couldn\'t get data from '. $this->name . $key );
                return false;
            }
        }


        /**
         * @param $key
         * @return int
         */
        public function count( $key ){
            try {
                if( $this->counter === null ){
                    $this->counter = intval( $this->client()->zcard( $this->name . $key ));
                    if(!$this->counter )
                        $this->counter = 0;
                }
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
            return $this->counter;
        }
    }
