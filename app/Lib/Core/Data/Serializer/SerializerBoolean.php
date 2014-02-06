<?php
    namespace Techy\Lib\Core\Data\Serializer;

    use Techy\Lib\Core\Data\AbstractSerializer;

    class SerializerBoolean extends AbstractSerializer {

        /**
         * return string
         */
        public function serialize( $data ){
            return $data ? 'TRUE' : 'FALSE';
        }

        /**
         * @param string $string
         * @return mixed
         */
        public function unserialize( $string ){
            return $string === 't';
        }
    }
