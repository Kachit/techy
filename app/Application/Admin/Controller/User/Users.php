<?php
    namespace Techy\Application\Admin\Controller\User;

    use Techy\Lib\Collection\User\UsersCollection;
    use Techy\Lib\Core\System\Registry;
    use Techy\Lib\Object\User\AuthUserObject;

    class Users extends Authorized {

        public function initialize(){
            $this->addRoute( static::METHOD_GET, 'main', 'main', 'admin/users/' );
        }

        protected function pageMain(){
            $UsersCollection = new UsersCollection();
            $users = $UsersCollection->select();

            $this->View->set('users', $users );
            $this->View->template('admin/users');
            return true;
        }
    }
