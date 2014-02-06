<?php
    namespace Techy\Application\Admin\Controller;

    use Techy\Lib\Core\Controller\AbstractWebController;
    use Techy\Lib\Core\Exception\e401;
    use Techy\Lib\Object\User\AdminObject;

    class AuthorizedWebController extends AbstractWebController {
        /**
         * @var AdminObject
         */
        protected $Auth;

        protected function preDispatch(){
            $Session = $this->getSession();

            if(!$this->Auth ){
                $this->Auth = new AdminObject();
                if( isset( $Session['user_id'] ))
                $this->Auth->loadById( $Session['user_id'] );
            }
            if(!$this->authorization())
                throw new e401();
        }

        protected function authorization(){
            return $this->Auth && $this->Auth->isReal();
        }

        protected function preDispatchNative(){
            $this->View->layout( 'index' );
        }

        protected function postDispatch(){
            if( $this->Auth && $this->Auth->isReal()){
                $this->getSession()->offsetSet( 'user_id', $this->Auth->getId());
            }
            else {
                $this->getSession()->offsetUnset( 'user_id' );
            }
        }
    }
