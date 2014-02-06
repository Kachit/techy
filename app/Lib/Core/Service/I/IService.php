<?php
	namespace Techy\Lib\Core\Service\I;

	interface IService {
        public function begin();
        public function commit();
        public function rollback();
	}
