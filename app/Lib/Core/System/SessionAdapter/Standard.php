<?php
    namespace Techy\Lib\Core\System\SessionAdapter;

    class Standard implements \Techy\Lib\Core\I\ISessionAdapter {

        public function offsetExists( $offset ){
            return isset( $_SESSION[ $offset ] );
        }

        public function offsetGet( $offset ){
            return isset( $_SESSION[ $offset ] ) ? $_SESSION[ $offset ] : null;
        }

        public function offsetSet( $offset, $value ){
            $_SESSION[ $offset ] = $value;
        }

        public function offsetUnset( $offset ){
            if( isset( $_SESSION[ $offset ] ) )
                unset( $_SESSION[ $offset ] );
        }

        public function start(){
            session_name( 'ssid' );
            session_start();
        }

        public function getName(){
            return session_id();
        }
    }
