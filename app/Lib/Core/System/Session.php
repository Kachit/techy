<?php
    namespace Techy\Lib\Core\System;

    use Techy\Lib\Core\I\IRequest;
    use Techy\Lib\Core\I\IResponse;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\SessionAdapter\Memory;
    use Techy\Lib\Core\System\SessionAdapter\Standard;

    class Session implements \Techy\Lib\Core\I\ISession {

        const
            SESSION_STANDARD = 0,
            SESSION_MEMORY = 1
        ;

        /**
         * @var Session
         */
        private static $Instance;

        /**
         * @var \Techy\Lib\Core\I\ISessionAdapter
         */
        private $SessionAdapter;

        private $cookies = array();

        protected function __construct( IRequest $Request, IResponse $Response ){
        }

        /**
         * @param IRequest $Request
         * @param IResponse $Response
         * @param int $sessionAdapter
         * @param null $storageType
         * @return Session
         */
        public static function instance( IRequest $Request, IResponse $Response, $sessionAdapter = self::SESSION_STANDARD, $storageType =  null){
            if(!self::$Instance ){
                self::$Instance = new self( $Request, $Response );
                self::$Instance
                    ->setAdapterType( $sessionAdapter, $storageType )
                    ->start()
                ;
            }
            return self::$Instance;
        }

        public function start(){
            $this->getAdapter()->start();
        }

        final public function offsetExists( $offset ){
            return $this->getAdapter()->offsetExists( $offset );
        }

        final public function offsetGet( $offset ){
            return $this->getAdapter()->offsetGet( $offset );
        }

        final public function offsetSet( $offset, $value ){
            $this->getAdapter()->offsetSet( $offset, $value );
        }

        final public function offsetUnset( $offset ){
            $this->getAdapter()->offsetUnset( $offset );
        }

        public function setCookie( $name, $value = null, $expire = null ){
            $this->cookies[ $name ] = array( 'value' => $value, 'expire' => $expire );
        }

        public function save(){
            // send cookies
            foreach( $this->cookies as $name => $cookie ){
                Application::instance()->getResponse()->cookie( $name, $cookie[ 'value' ], $cookie[ 'expire' ] );
            }
            $this->getAdapter()->offsetSet( Memory::SESSK_MODIFIED, time());
            return true;
        }

        public function getName(){
            return $this->getAdapter()->getName();
        }

        /**
         * @param $adapterType
         * @param $storageType
         * @return $this
         */
        public function setAdapterType( $adapterType, $storageType ){
            switch( $adapterType ){
                case self::SESSION_STANDARD:
                    $this->setAdapter(new Standard());
                    break;

                case self::SESSION_MEMORY:
                    $this->setAdapter(new Memory($storageType));
                    break;
            }
            return $this;
        }

        /**
         * @return \Techy\Lib\Core\I\ISessionAdapter
         */
        public function getAdapter(){
            return $this->SessionAdapter;
        }

        public function setAdapter($adapter){
            $this->SessionAdapter = $adapter;
        }
    }
