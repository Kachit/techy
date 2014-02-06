<?php
    namespace Techy\Lib\Core\Data\Serializer;

    use Techy\Lib\Core\Data\AbstractSerializer;

    class SerializerVector extends AbstractSerializer {

        protected
            $leftBracket = '(',
            $rightBracket = ')'
        ;

        /**
         * return string
         */
        public function serialize( $data ){
            return $this->leftBracket . implode( ',', $data ) . $this->rightBracket;
        }

        /**
         * @param string $string
         * @return mixed
         */
        public function unserialize( $string ){
            return explode( ',', trim( $string, $this->leftBracket . $this->rightBracket ));
        }

        /**
         * @param string $brackets
         * @return mixed
         */
        public function setBrackets( $brackets ){
            $this->leftBracket = substr( $brackets, 0, 1 );
            $this->rightBracket = substr( $brackets, 1, 1 );
        }
    }
