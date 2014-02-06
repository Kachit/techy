<?php
    namespace Techy\Script\Database;

    /**
     * Only for dev platform and dev purposes
     */
    class ActualizeAllScript extends \Techy\Script\Base {

        public function run(){
            // main
            $ActualizeMain = new ActualizeMainScript();
            $ActualizeMain->run();
        }
    }
