<?php
    namespace Techy\Lib\Core\Data;

    use Techy\Lib\Core\Service\Redis\Key;

    abstract class RedisObject extends AbstractObject {

        protected static $name = null;

        /**
         * @var Key $Service
         */
        protected $Service;

        protected
            $loadedData,
            $primaryKey
        ;

        protected function getService(){
            if(!$this->Service )
                $this->Service = new Key( static::$name );

            return $this->Service;
        }

        protected function setPrimary( $offset ){
            if(!$this->offsetExists( $offset ))
                throw new \UnexpectedValueException( 'There is no field '. $offset .' in '. get_class( $this ));

            $this->primaryKey = $offset;
            return $this;
        }

        public function getId(){
            if(!$this->primaryKey )
                throw new \UnexpectedValueException( 'Primary key is not set for "'. get_class( $this ));

            return $this[$this->primaryKey];
        }

        /**
         * Returns false on failure
         * Returns true on success
         *
         * @param $id
         * @return bool
         * @throws \UnexpectedValueException
         */
        public function loadById( $id ){
            if(!$this->primaryKey )
                throw new \UnexpectedValueException( 'Primary key is not set for "'. get_class( $this ));

            $data = $this->getService()->get( $id );
            if(!$data )
                return false;

            $data[$this->primaryKey] = $id;

            $this->fetch( $data );
            $this->loadedData = $this->export();
            return true;
        }

        /**
         * Returns false on failure
         * Returns true on success
         *
         * @return bool
         */
        public function update(){
            $data = $this->export();
            unset( $data[$this->primaryKey] );
            if( false === $this->getService()->set( $this->getId(), $data ))
                return false;

            $this->loadedData = $this->export();
            return true;
        }

        /**
         * Returns false on failure
         * Returns count of affected rows
         *
         * @return bool|int
         * @throws \UnexpectedValueException
         */
        public function delete(){
            if(!$this->primaryKey )
                throw new \UnexpectedValueException( 'Primary key is not set for '. get_class( $this ));

            return $this->getService()->remove( $this->getId());
        }

        /**
         * @return bool
         */
        public function isLoaded(){
            return !empty( $this->loadedData );
        }
    }
