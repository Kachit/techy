<?php
    namespace Techy\Lib\Core\Data\Filter;

    use Techy\Lib\Core\Data\AbstractFilter;

    class FilterLowercaseTrim extends AbstractFilter {

        /**
         * @param $value
         * @return string
         */
        public function filter( $value ){
            return mb_strtolower( trim( $value ));
        }
    }
