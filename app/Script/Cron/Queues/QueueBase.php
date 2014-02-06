<?php
    namespace Techy\Script\Cron\Queues;

    use Techy\Lib\Core\Exception\QueueError;
    use Techy\Lib\Core\Service\Redis\Lock;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\Registry;
    use Techy\Script\Base;

    abstract class QueueBase extends Base {

        protected static $queueName;
        protected static $pullLength = 1;

        private $master;
        private $masterPid;

        /**
         * @var \Techy\Lib\Core\Data\QueueObject $Queue
         */
        private $Queue;

        /**
         * @var \Techy\Lib\Core\Service\Redis\Lock $Lock
         */
        private $Lock;

        /**
         * @abstract
         * @param array $item
         */
        abstract protected function runQueue( array $item );

        final protected function repush(){
            $this->Queue->push();
        }

        final public function run( $once = false ){
            try {
                $this->Lock = new Lock( static::$queueName );
                $this->masterPid = Registry::instance()->Date()->timestamp();
                $this->master = $this->Lock->set( $this->masterPid );

                $className = '\\Techy\\Lib\\Object\\'. static::$queueName;
                $this->Queue = new $className();
                if(!$this->master ){
                    if(!$this->Queue->len())
                        return;
                }
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return;
            }
            while( 1 ){
                if(!$this->cycle() || $once )
                    break;

                sleep(1);
            }
        }

        final private function cycle(){
            try {
                while( $this->Queue->pull( static::$pullLength ))
                    $this->runQueue( $this->Queue->export());

                if(!$this->master || $this->masterPid != $this->Lock->get())
                    return false;
            }
            catch( QueueError $E ){
                if( $this->master )
                    $this->Lock->del();
                return false;
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
            }
            return true;
        }
    }
