<?php
	namespace Techy\Lib\Core\View;

	class Blitz extends \Techy\Lib\Core\View\HtmlBase {
        protected function html(){
            $Template = new \Techy\Lib\Core\Api\Blitz( $this->template, $this->pathTemplate, self::TEMPLATE_EXT );
            $Template->set( $this->data );
            $this->page['yield'] = $Template->parse();
            $this->page['globals'] = $this->globals;

            if( $this->pathLayout ){
                $Layout = new \Techy\Lib\Core\Api\Blitz( $this->layout, $this->pathLayout, self::TEMPLATE_EXT );
                $Layout->set( $this->page );
                return $Layout->parse();
            }
            else
                return $this->page['yield'];
        }
    }
	