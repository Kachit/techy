<?php
    namespace Techy\Lib\Core\Data;

    abstract class DatabaseCollection extends AbstractCollection {
        /**
         * @var \Techy\Lib\Core\Service\I\IService
         */
        abstract protected function getService();
    }
