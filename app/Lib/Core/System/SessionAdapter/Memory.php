<?php
    namespace Techy\Lib\Core\System\SessionAdapter;

    use Techy\Lib\Core\I\ISessionAdapterStorage;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\Registry;
    use Techy\Lib\Core\System\SessionAdapter\Storage\Memcached as StorageMemcached;
    use Techy\Lib\Core\System\SessionAdapter\Storage\Redis as StorageRedis;

    class Memory implements \Techy\Lib\Core\I\ISessionAdapter{

        const
            SESSION_EXPIRE = 2592000, // 30 days
            COOKIE_KEY = 'ssid',
            MEMK_SESSION = 'sess:',
            SESSK_MODIFIED = 'modified', // last access time
            SESSK_USER = 'user', // user_id
            SESSK_LANG = 'lang', // user_id
            STORAGE_MEMCACHED = 0,
            STORAGE_REDIS = 1
        ;

        private $cookie = false;

        /**
         * @var ISessionAdapterStorage
         */
        private $Storage;

        public function __construct( $storageType = self::STORAGE_MEMCACHED ){
            switch( $storageType ){
                case self::STORAGE_MEMCACHED:
                    $this->setStorage( new StorageMemcached() );
                    break;

                case self::STORAGE_REDIS:
                    $this->setStorage( new StorageRedis() );
                    break;
            }
        }

        /**
         * @param $Storage
         */
        private function setStorage( ISessionAdapterStorage $Storage ){
            $this->Storage = $Storage;
        }

        /**
         * @return ISessionAdapterStorage
         */
        private function getStorage(){
            return $this->Storage;
        }

        /**
         * @param mixed $offset
         * @return bool
         */
        public function offsetExists( $offset ){
            return $this->offsetGet( $offset ) === null ? false : true;
        }

        /**
         * @param mixed $offset
         * @return mixed|null
         */
        public function offsetGet( $offset ){
            $data = $this->getStorage()->readData( $this->getKey() );
            if( isset( $data[ $offset ] ) ){
                return $data[ $offset ];
            }
            return null;
        }

        /**
         * @param mixed $offset
         * @param mixed $value
         */
        public function offsetSet( $offset, $value ){
            $data = $this->getStorage()->readData( $this->getKey() );
            $data[ $offset ] = $value;
            $this->getStorage()->writeData( $this->getKey(), $data, self::SESSION_EXPIRE );
        }

        /**
         * @param mixed $offset
         */
        public function offsetUnset( $offset ){
            $data = $this->getStorage()->readData( $this->getKey() );
            if( array_key_exists( $offset, $data ) ){
                unset( $data[ $offset ] );
                $this->getStorage()->writeData( $this->getKey(), $data, self::SESSION_EXPIRE );
            }
        }

        public function start(){
            if( !$this->getName() ){ // если не установлена клиентская кука
                $uc = $this->generateClientHash(); // сгенерим её
                $this->setName( $uc ); // и установим
            }

            if( (int)$this->offsetGet( self::SESSK_USER ) ){ // если юзер был уже авторизован
                $this->offsetSet( self::SESSK_MODIFIED, time() );
            }
            $this->create();
        }

        /**
         * @return bool
         */
        public function getName(){
            if( $this->cookie ){
                return $this->cookie;
            }
            return ( isset( $_COOKIE[ self::COOKIE_KEY ] ) ? $_COOKIE[ self::COOKIE_KEY ] : false );
        }

        /**
         * @param $uc
         */
        private function setName( $uc ){
            $this->cookie = $uc;
            Application::instance()->getResponse()->cookie( self::COOKIE_KEY, $uc );
        }

        private function create(){
            if( !$this->getStorage()->exists( $this->getKey() ) ){
                $this->getStorage()->writeData( $this->getKey(), array( self::SESSK_MODIFIED => time() ), self::SESSION_EXPIRE );
            }
        }

        /**
         * @return string
         */
        private function getKey(){
            return self::MEMK_SESSION . md5( \Techy\Lib\Core\System\Server::crypt( $this->getName()));
        }

        /**
         * @return string
         */
        private function generateClientHash(){
            mt_srand( (double)microtime() * 10000 );
            return md5( uniqid( rand(), true ) );
        }
    }

