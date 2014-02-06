<?php
    namespace Techy\Application\Admin\Controller\Authorization;

    use Techy\Lib\Core\Exception\e404;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Object\MailQueue;
    use Techy\Lib\Object\User\AdminObject;

    class PasswordRecovery extends Unauthorized {

        public function initialize(){
            $this->addRoute( static::METHOD_GET, 'newPassword', 'newPassword', 'auth/recovery/<\d+>/<\w+>', array( 'user_id', 'code' ));
            $this->addRoute( static::METHOD_GET, 'passRecovery', 'passRecovery', 'auth/recovery');
            $this->addRoute( static::METHOD_POST, 'newPasswordSubmit', 'newPasswordSubmit', 'auth/recovery/<\d+>/<\w+>', array( 'user_id', 'code' ))->setView( 'Json' );
            $this->addRoute( static::METHOD_POST, 'passRecoverySubmit', 'passRecoverySubmit', 'auth/recovery' )->setView( 'Json' );
        }

        protected function pagePassRecovery(){
            $this->View->template('auth/password_recovery');
            $this->View->globals('controller','PasswordRecovery');
            return true;
        }

        protected function pagePassRecoverySubmit(){
            $post = Application::instance()->getRequest()->getPostParams();
            if(!isset( $post['login'] ))
                $this->throwClientError( 'auth.forms.general' );

            $Auth = new AdminObject();
            if( $Auth->loadByEmail( $post['login'] )){
                $recoveryData = $Auth->getPasswordRecoveryData();
                if(!$recoveryData )
                    $this->throwClientError( 'auth.forms.general' );

                $MailQueue = new MailQueue();
                $MailQueue->fetch( array(
                    'email' => $Auth['login'],
                    'subject' => 'Восстановление пароля',
                    'template' => 'password_recovery',
                    'data' => $recoveryData + array(
                        'name' => $Auth['data']['name'],
                    )
                ));
                $MailQueue->push();
            }

            $this->View->set( 'success', 'auth.forms.password_sent' );
            return true;
        }

        protected function pageNewPassword(){
            $Auth = new AdminObject();
            if(!$Auth->loadById( $this->queryParam( 'user_id' )))
                throw new e404();

            $this->View->set( $this->queryParam());
            $this->View->template('auth/new_password');
            $this->View->globals('controller','NewPassword');
            return true;
        }

        protected function pageNewPasswordSubmit(){
            $post = Application::instance()->getRequest()->getPostParams();
            if( empty( $post['password'] ))
                $this->throwValidationError( array( 'password' => 'auth.forms.password.notEmpty' ));

            $Auth = new AdminObject();
            if(!$Auth->loadById( $this->queryParam( 'user_id' )))
                $this->throwClientError( 'auth.forms.general' );

            $Auth->begin();
            if(!$Auth->checkPasswordRecoveryCode( $this->queryParam( 'code' ))){
                $Auth->rollback();
                $this->throwClientError( 'auth.forms.general' );
            }

            $Auth['password'] = $post['password'];
            $Auth->hashPassword();
            if(!$Auth->update()){
                $Auth->rollback();
                $this->throwClientError( 'auth.forms.general' );
            }
            $Auth->commit();
            $this->Auth = $Auth;

            $MailQueue = new MailQueue();
            $MailQueue->fetch( array(
                'email' => $Auth['login'],
                'subject' => 'Пароль изменен',
                'template' => 'password_changed',
                'data' => array(
                    'name' => $Auth['data']['name'],
                ),
            ));
            $MailQueue->push();

            $this->View->set( 'success', 'auth.forms.password_changed' );
            return true;
        }
    }
