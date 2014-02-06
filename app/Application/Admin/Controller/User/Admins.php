<?php
    namespace Techy\Application\Admin\Controller\User;

    use Techy\Lib\Collection\User\AdminsCollection;
    use Techy\Lib\Core\System\Registry;
    use Techy\Lib\Object\MailQueue;
    use Techy\Lib\Object\User\AdminObject;

    class Admins extends Authorized {

        public function initialize(){
            $this->addRoute( static::METHOD_GET, 'main', 'main', 'admin/admins/' );
            $this->addRoute( static::METHOD_POST, 'create', 'create', 'admin/admins/' )->setView('Json');
        }

        protected function pageMain(){
            $AdminsCollection = new AdminsCollection();
            $admins = $AdminsCollection->select();

            $this->View->set('admins', $admins );
            $this->View->template('admin/admins');
            $this->View->globals('controller','Admins');
            return true;
        }

        protected function pageCreate(){
            $Auth = new AdminObject();
            $Auth->fetch( $this->getRequest()->getPostParams());
            $Auth['password'] = $Auth->generatePassword();
            $password = $Auth->hashPassword();
            if(!$Auth->validate())
                $this->throwValidationError( $Auth->getValidationErrors());
            if(!$Auth->create())
                $this->throwClientError( 'auth.forms.general' );
            if(!$Auth->isReal())
                $this->throwValidationError(array('login' => 'auth.forms.email.busy'));

            $MailQueue = new MailQueue();
            $MailQueue->fetch( array(
                'email' => $Auth['login'],
                'subject' => 'Ваши данные для входа на сайт',
                'template' => 'sign_up',
                'password'  => $password
            ));
            $MailQueue->push();

            $this->View->set('admin', $Auth->export());
            return true;
        }
    }
