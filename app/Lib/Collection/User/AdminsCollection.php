<?php
    namespace Techy\Lib\Collection\User;

    use Techy\Lib\Core\Data;
    use Techy\Lib\Object\User\AdminObject;
    use Techy\Lib\Service\User\AdminService;

    class AdminsCollection extends Data\DatabaseCollection {

        private
            $Service,
            $Object
        ;

        protected function getObject(){
            if(!$this->Object )
                $this->Object = new AdminObject();

            return $this->Object;
        }

        /**
         * @return AdminService
         */
        protected function getService(){
            if(!$this->Service )
                $this->Service = new AdminService( 'main' );

            return $this->Service;
        }

        protected function init(){

        }

        public function select(){
            return $this->processCollection( $this->getService()->select( $this->export()));
        }
    }
