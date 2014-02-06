<?php
    namespace Techy\Lib\Core\I;

    interface ISessionAdapter extends \ArrayAccess {

        public function start();

        public function getName();
    }
