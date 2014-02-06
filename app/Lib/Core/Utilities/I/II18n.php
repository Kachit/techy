<?php
    namespace Techy\Lib\Core\Utilities\I;

    interface II18n {
        /**
         * @return string
         */
        public function get();

        /**
         * @return array|string
         */
        public function pick();
    }