<?php
    namespace Techy\Lib\Core\Controller;

    use Techy\Config\Domain;
    use Techy\Lib\Core\Exception\e401;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\Controller\AbstractController;

    abstract class AbstractWebController extends AbstractController {
        protected function preDispatch(){
            if(!$this->authorization())
                throw new e401();
        }

        protected function authorization(){
            return true;
        }

        protected function postDispatchNative(){
            $this->View->globals( 'domain_url', Domain::instance()->main());
            $this->View->globals( 'base_url', Application::instance()->getRouter()->getUrl());
        }
    }
