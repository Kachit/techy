<?php
    namespace Techy\Lib\Core\Controller;

	class Route {

        const
            PARAM_PAGE_METHOD = 0,
            PARAM_PAGE_NAME = 1,
            PARAM_URL_TEMPLATE = 2,
            PARAM_PARAMS = 3,
            PARAM_VIEW = 4
        ;

        private
            $pageMethod,
            $pageName,
            $urlTemplate,
            $params,
            $view = 'Native';

        public function __construct( $pageMethod, $pageName, $urlTemplate, $params = [] ){
            $this->pageMethod = $pageMethod;
            $this->pageName = $pageName;
            $this->urlTemplate = $urlTemplate;
            $this->params = $params;
        }

        public function setView( $view ){
            $this->view = $view;
        }

        public function getView(){
            return $this->view;
        }

        public function getMethod(){
            return 'page'. ucfirst( $this->pageMethod );
        }

        public function getPackedParams(){
            return [
                self::PARAM_PAGE_METHOD => $this->pageMethod,
                self::PARAM_PAGE_NAME => $this->pageName,
                self::PARAM_URL_TEMPLATE => trim( $this->urlTemplate, '/' ),
                self::PARAM_PARAMS => $this->params,
                self::PARAM_VIEW => $this->view,
            ];
        }
    }
