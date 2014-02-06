<?php
    namespace Techy\Application\Admin\Controller\Authorization;

    use Techy\Lib\Object\User\AdminObject;

    class SignIn extends Unauthorized {

        public function initialize(){
            $this->addRoute( static::METHOD_POST, 'signInSubmit', 'signInSubmit', 'auth/signin' )->setView( 'Json' );
        }

        protected function pageSignInSubmit(){
            $post = $this->getApplication()->getRequest()->getPostParams();
            if( empty( $post['login'] ) || empty( $post['password'] ))
                $this->throwClientError( 'auth.forms.general' );

            $Auth = new AdminObject();
            if(!$Auth->loadByEmail( $post['login'], $post['password'] ))
                $this->throwClientError( 'auth.forms.wrong_data' );

            $this->Auth = $Auth;
            $this->View->set('redirect', '/'. $this->getApplication()->getRouter()->getRoute( 'get', 'User\\Home', 'home' ));
            return true;
        }
    }
