<?php
    namespace Techy\Lib\Core\Data\Filter;

    use Techy\Lib\Core\Data\AbstractFilter;

    class FilterDigits extends AbstractFilter {

        /**
         * @param $value
         * @return string
         */
        public function filter( $value ){
            return preg_replace( '#\D#', '', $value );
        }
    }
