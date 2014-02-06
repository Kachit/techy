<?php
    namespace Techy\Lib\Core\Service\Redis;

	use Techy\Lib\Core\System\Application;

    class Sequence extends Base implements \Techy\Lib\Core\Service\I\ISequence {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_sequence_'. $name;
        }

        public function allocateId(){
            try {
                $id = $this->client()->incr( $this->name );
                if( $id )
                    return intval( $id );

                throw new \RuntimeException( 'Couldn\'t allocate id for '. $this->name );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function lastId(){
            try {
                $id = $this->client()->get( $this->name );
                if( $id )
                    return intval( $id );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
            }
            return false;
        }
    }
