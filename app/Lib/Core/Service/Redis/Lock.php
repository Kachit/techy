<?php
    namespace Techy\Lib\Core\Service\Redis;

    use Techy\Lib\Core\System\Application;

    class Lock extends Base {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_lock_'. $name;
        }

        public function set( $value = 1 ){
            try {
                return $this->client()->setnx( $this->name, $value );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function get(){
            try {
                return $this->client()->get( $this->name );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }

        public function del(){
            try {
                return $this->client()->del( $this->name );
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                return false;
            }
        }
    }
