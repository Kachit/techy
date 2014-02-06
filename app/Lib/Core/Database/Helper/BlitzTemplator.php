<?php
	namespace Techy\Lib\Core\Database\Helper;

	class BlitzTemplator implements \Techy\Lib\Core\Database\I\ISQLTemplator {

        /**
         * @var \Techy\Lib\Core\Database\I\IConfig
         */
        private $Config;

        /**
         * @param \Techy\Lib\Core\Database\I\IConfig $Config
         */
        public function __construct( \Techy\Lib\Core\Database\I\IConfig $Config ){
            $this->Config = $Config;
        }

        /**
         * @param string $tpl
         * @param array $args
         * @param mixed $spotId
         * @return string
         */
        public function parseSQL( $tpl, $args, $spotId = null ){
            if( empty( $args ) && false === strpos( $tpl, '{' ))
                return $tpl;

            $T = new Blitz( $this->Config, $spotId );
            $T->load( $tpl );
            $T->set( $args );

            return $T->parse();
        }
    }
	