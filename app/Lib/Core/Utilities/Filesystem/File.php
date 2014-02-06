<?php

    namespace Techy\Lib\Core\Utilities;

    use Techy\Lib\Core\Utilities\Filesystem\I\IFsObject;

    class File implements IFsObject {

        protected $files;
        protected $name;

        public function __construct( $name ){
            $this->name = $name;
        }

        public function scanDir( $dir ){
            $files = scandir( $dir );
            foreach( $files as $file ){
                if( is_dir($dir . $file) ){
                    $fileObj = new \SplFileObject($dir . $file);
                    $fileObj->
                }
            }
        }


    }