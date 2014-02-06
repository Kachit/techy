<?php
    namespace Techy\Application\Admin\Controller\User;

    use Techy\Application\Admin\Controller\AuthorizedWebController;

    class Authorized extends AuthorizedWebController {

        protected function preDispatchNative(){
            parent::preDispatchNative();
            $this->View->layout('user');
        }

        protected function postDispatchNative(){
            parent::postDispatchNative();
            $this->View->page('user', $this->Auth );
        }
    }
