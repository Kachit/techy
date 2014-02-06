<?php
	namespace Techy\Lib\Core\Service\I;

	interface IObjectService extends IService {
        /**
         * @abstract
         * @param array $data
         * @return bool|array
         */
        public function load( array $data );

        /**
         * @abstract
         * @param array $data
         * @return bool|string
         */
        public function create( array $data );

        /**
         * @abstract
         * @param array $data
         * @param array|null $by
         * @return bool|int
         */
        public function update( array $data, array $by = null );

        /**
         * @abstract
         * @param array $by
         * @return bool|int
         */
        public function delete( array $by );
	}
	