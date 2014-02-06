<?php
	namespace Techy\Lib\Core\I;

	interface IHtmlView extends \Techy\Lib\Core\I\IView {

        /**
         * Set template
         *
         * @abstract
         * @param string $name
         */
        public function template( $name );

        /**
         * Set layout
         *
         * @abstract
         * @param string $name
         */
        public function layout( $name );

        /**
         * Set globals data param
         *
         * @abstract
         * @param string $name
         * @param mixed $value
         */
        public function globals( $name, $value );

        /**
         * Set layout data param
         *
         * @abstract
         * @param string $name
         * @param mixed $value
         * @return mixed
         */
        public function page( $name, $value = null );
	}
	