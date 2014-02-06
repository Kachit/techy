<?php
    namespace Techy\Application\Admin\Controller\Authorization;

    use Techy\Application\Admin\Controller\AuthorizedWebController;

    class Unauthorized extends AuthorizedWebController {

        protected function authorization(){
            if( $this->Auth && $this->Auth->isReal())
                $this->redirect( 'user\\Home', 'home' );

            return true;
        }
    }
