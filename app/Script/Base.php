<?php
    namespace Techy\Script;

    use Techy\Lib\Core\System\Registry;

    abstract class Base {
        abstract public function run();

        /**
         * @param $name
         * @return \Techy\Lib\Core\Database\I\IDatabase
         */
        final protected function db( $name ){
            return Registry::instance()->DatabaseConnection()->get( $name );
        }

        /**
         * @param $name
         * @param $spotId
         * @return \Techy\Lib\Core\Database\I\IDatabase
         */
        final protected function spotDb( $name, $spotId ){
            return Registry::instance()->DatabaseSpotConnection()->get( $name, $spotId );
        }
    }
