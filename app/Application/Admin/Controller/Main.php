<?php
    namespace Techy\Application\Admin\Controller;

    class Main extends AuthorizedWebController {

        public function initialize(){
            $this->addRoute( static::METHOD_GET, 'main', 'main', '' );
        }

        protected function authorization(){
            return true;
        }

        protected function pageMain(){
            if( $this->Auth && $this->Auth->isReal())
                $this->redirect( 'user\\Home', 'home' );

            $this->View->template('main');
            $this->View->globals('controller','SignIn');
            return true;
        }
    }
