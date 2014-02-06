<?php

    namespace Techy\Lib\Core\Utilities\Filesystem;


    use Techy\Lib\Core\Service\Redis\HashAuthorizer;
    use Techy\Lib\Core\Utilities\Filesystem\I\IFsObject;

    class Directory extends \DirectoryIterator implements IFsObject {

        private $nodes;

        private $name;
        private $instance;

        public function __construct( $name ){
            $this->name = $name;
            $this->instance = new \DirectoryIterator( $this->name );
            $this->nodes = new \ArrayObject();
        }

        public function scanDir( $dir ){
            $DirectoryIterator = new \DirectoryIterator( $dir );
            foreach( $DirectoryIterator as $Current ){
                if( $Current->isDot || !$Current->isDot() ){
                    continue;
                }

                if( $Current->isDir() ){
                    $item = new self( $Current->getBasename() );
                } else {
                    $item = new File
                }
            }
        }

        protected function append( IFsObject $file){
//            $this->nodes->a
        }

    }