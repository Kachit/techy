<?php
    namespace Techy\Application\Admin\Controller\User;

    use Techy\Lib\Collection\Book\AuthorsCollection;
    use Techy\Lib\Collection\Book\CopiesCollection;

    class Home extends Authorized {

        public function initialize(){
            $this->addRoute( static::METHOD_GET, 'home', 'home', 'admin/home/' );
            $this->addRoute( static::METHOD_GET, 'signout', 'signout', 'admin/signout/' );
        }

        protected function pageHome(){
            $this->View->template('admin/home');
            return true;
        }

        protected function pageSignout(){
            $this->getSession()->offsetUnset('user_id');
            $this->redirect( 'main', 'main' );
            return true;
        }
    }
