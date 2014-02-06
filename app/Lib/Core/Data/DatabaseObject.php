<?php
    namespace Techy\Lib\Core\Data;

    use Techy\Lib\Core\Service\ObjectService;

    abstract class DatabaseObject extends AbstractObject {
        protected static
            $db = 'main',
            $table = null
        ;

        /**
         * @var \Techy\Lib\Core\Service\I\IObjectService $Service
         */
        protected $Service;

        protected
            $loadedData,
            $primaryKey
        ;

        private
            $serializers = array(),
            $autoIncrement
        ;

        protected function getService(){
            if(!$this->Service )
                $this->Service = new ObjectService( static::$db, static::$table );

            return $this->Service;
        }

        protected function setPrimary( $offset, $autoIncrement = true ){
            if(!$this->offsetExists( $offset ))
                throw new \UnexpectedValueException( 'There is no field '. $offset .' in '. get_class( $this ));

            $this->primaryKey = $offset;
            $this->autoIncrement = $autoIncrement;
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

            $data = $this->getService()->load( array( $this->primaryKey => $id ));
            if(!$data )
                return false;

            $this->fetchLoadedData( reset( $data ));
            return true;
        }

        /**
         * Returns false on failure
         * Returns true on success
         *
         * @return bool
         */
        public function create(){
            $insertedId = $this->getService()->create( $this->exportSerializedData());
            if( false === $insertedId )
                return false;

            if( $this->autoIncrement )
                $this->offsetSet( $this->primaryKey, $insertedId );

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
        public function update(){
            if(!$this->primaryKey )
                throw new \UnexpectedValueException( 'Primary key is not set for '. get_class( $this ));

            if(!$this->loadedData )
                throw new \UnexpectedValueException( 'Object must be loaded before update for '. get_class( $this ));

            $data = $this->exportSerializedData();
            $id  = $data[$this->primaryKey];
            unset( $data[$this->primaryKey] );

            return $this->getService()->update( $data, array( $this->primaryKey => $id ));
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

            return $this->getService()->delete( array( $this->primaryKey => $this->getId()));
        }

        /**
         * @return bool
         */
        public function isLoaded(){
            return !empty( $this->loadedData );
        }

        /**
         * Fetches the data using serializers
         *
         * @param $data
         */
        final protected function fetchLoadedData( $data ){
            foreach( $this->serializers as $offset => $Serializer ){
                /**
                 * @var AbstractSerializer $Serializer
                 */
                if( isset( $data[$offset] ) && null !== $data[$offset] )
                    $data[$offset] = $Serializer->unserialize( $data[$offset] );
            }
            $this->fetch( $data );
            $this->loadedData = $this->export();
        }

        /**
         * Exports the data using serializers
         */
        final protected function exportSerializedData(){
            $data = $this->export();
            foreach( $this->serializers as $offset => $Serializer ){
                /**
                 * @var AbstractSerializer $Serializer
                 */
                if( $data[$offset] !== null )
                    $data[$offset] = $Serializer->serialize( $data[$offset] );
            }
            return $data;
        }

        /**
         * @param string $offset
         * @param string $type
         * @param array $options
         * @return $this
         * @throws \UnexpectedValueException
         */
        final protected function addSerializer( $offset, $type, array $options = array()){
            if(!$this->offsetExists( $offset ))
                throw new \UnexpectedValueException( 'There is no field "'. $offset .'" to add a serializer for '. get_class( $this ));

            if( array_key_exists( $offset, $this->serializers ))
                throw new \UnexpectedValueException( 'Serializer for field "'. $offset .'" has been added already' );

            $this->serializers[$offset] = AbstractSerializer::create( $type, $options );
            return $this;
        }
    }
