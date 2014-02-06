<?php
	namespace Techy\Lib\Core\View;

	use Techy\Lib\Core\System\Application;

    class Json implements \Techy\Lib\Core\I\IView {
        protected $data = array();

        public function set( $name, $value = null ){
            if( is_array( $name ))
                $this->data += $name;
            else
                $this->data[$name] = $value;
        }

        public function render(){
            Application::instance()->getResponse()->header( 'Content-type: application/json; charset=UTF-8' );

            return json_encode( $this->data );
        }
	}
	