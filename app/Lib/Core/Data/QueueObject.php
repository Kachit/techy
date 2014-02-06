<?php
    namespace Techy\Lib\Core\Data;

    use Techy\Lib\Core\Service\Redis\Queue;

    abstract class QueueObject extends AbstractObject {
        /**
         * @var \Techy\Lib\Core\Service\I\IQueue
         */
        protected $Service;

        /**
         * @var AbstractSerializer
         */
        protected $Serializer;

        /**
         * @var array
         */
        private $serializers = array();

        protected function getService(){
            if(!$this->Service )
                $this->Service = new Queue( static::$queueName );

            return $this->Service;
        }

        protected function getSerializer(){
            if(!$this->Serializer )
                $this->Serializer = AbstractSerializer::create( AbstractSerializer::SERIALIZER_COMMON );

            return $this->Serializer;
        }

        /**
         * @var string
         */
        protected static $queueName = null;

        /**
         * @var array
         */
        protected $pullStack = null;

        /**
         * @var array
         */
        protected $pushStack = array();

        /**
         * @param bool $stacking
         * @return bool
         */
        public function push( $stacking = false ){
            if(!$this->validate())
                return false;

            $data = $this->export();
            foreach( $this->serializers as $offset => $Serializer ){
                /**
                 * @var AbstractSerializer $Serializer
                 */
                if( $data[$offset] !== null )
                    $data[$offset] = $Serializer->serialize( $data[$offset] );
            }

            if( $stacking ){
                $this->pushStack[] = array(
                    'data' => $this->getSerializer()->serialize( $data ),
                    'options' => $this->getOptions(),
                );
                return true;
            }else
                return $this->getService()->push( $this->getSerializer()->serialize( $data ), $this->getOptions());
        }

        public function pushStack(){
            if( empty( $this->pushStack ))
                return true;

            $result = call_user_func_array( array( $this->getService(), 'pushStack' ), $this->pushStack );
            $this->pushStack = array();
            return $result;
        }

        /**
         * @param int $count
         * @return bool
         */
        public function pull( $count = 1 ){
            if( empty( $this->pullStack )){
                $this->pullStack = $this->getService()->pull( $count );
                if( empty( $this->pullStack ))
                    return false;
            }
            $data = $this->getSerializer()->unserialize( array_shift( $this->pullStack ));
            foreach( $this->serializers as $offset => $Serializer ){
                /**
                 * @var AbstractSerializer $Serializer
                 */
                if( isset( $data[$offset] ) && null !== $data[$offset] )
                    $data[$offset] = $Serializer->unserialize( $data[$offset] );
            }
            $this->fetch( $data );
            return true;
        }

        /**
         * @return int
         */
        public function len(){
            return $this->getService()->len();
        }

        /**
         * @return array
         */
        protected function getOptions(){
            return array();
        }
    }
