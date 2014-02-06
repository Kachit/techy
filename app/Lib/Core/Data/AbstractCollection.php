<?php
    namespace Techy\Lib\Core\Data;

    abstract class AbstractCollection extends AbstractObject {

        /**
         * Overwrite this method to return Object
         *
         * @return AbstractObject
         */
        abstract protected function getObject();

        protected function processCollection( $objects ){
            $Object = $this->getObject();
            foreach( $objects as &$object )
                $object = $Object->fetch( $object )->export();
            return $objects;
        }
    }
