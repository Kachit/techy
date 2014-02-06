<?php
    namespace Techy\Lib\Core\Data\Serializer;

    use Techy\Lib\Core\Data\AbstractSerializer;

    class SerializerCommon extends AbstractSerializer {
        /**
         * return string
         */
        public function serialize( $data ){
            return serialize( $data );
        }

        /**
         * @param string $string
         * @return mixed
         */
        public function unserialize( $string ){
            return unserialize( $string );
        }
    }
