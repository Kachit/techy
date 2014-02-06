<?php
    namespace Techy\Lib\Core\Data\Filter;

    use Techy\Lib\Core\Data\AbstractFilter;

    class FilterUrl extends AbstractFilter {

        /**
         * @param $value
         * @return string
         */
        public function filter( $value ){
            if( preg_match( '#^https?\:\/\/#', $value ))
                return $value;

            return 'http://'. $value;
        }
    }
