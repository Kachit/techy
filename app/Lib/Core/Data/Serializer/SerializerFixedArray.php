<?php
    namespace Techy\Lib\Core\Data\Serializer;

    use Techy\Lib\Core\Data\AbstractSerializer;

    class SerializerFixedArray extends AbstractSerializer {

        protected $fields = array();

        /**
         * return string
         */
        public function serialize( $data ){
            $implode = array();
            foreach( $this->fields as $field )
                $implode[] = $data[$field];
            return implode( ',', $implode );
        }

        /**
         * @param string $string
         * @return mixed
         */
        public function unserialize( $string ){
            return array_combine( $this->fields, explode( ',', $string ));
        }

        /**
         * @param array $fields
         * @throws \UnexpectedValueException
         */
        public function setFields( array $fields ){
            $this->fields = $fields;
        }
    }
