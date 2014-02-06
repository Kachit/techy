<?php
    namespace Techy\Lib\Collection\User;

	use Techy\Lib\Core\Data;
    use Techy\Lib\Object\User\AuthUserObject;
    use Techy\Lib\Service\User\AuthService;

    class UsersCollection extends Data\DatabaseCollection {

        private
            $Service,
            $Object
        ;

        protected function getObject(){
            if(!$this->Object )
                $this->Object = new AuthUserObject();

            return $this->Object;
        }

        /**
         * @return AuthService
         */
        protected function getService(){
            if(!$this->Service )
                $this->Service = new AuthService( 'main' );

            return $this->Service;
        }

        protected function init(){
            $this
                ->addField( 'group_id', Data\AbstractField::FIELD_INT )
            ;
        }

        public function select(){
            return $this->processCollection( $this->getService()->select( $this->export()));
        }
    }
