<?php
    namespace Techy\Lib\Core\View;

    use Techy\Lib\Core\System\Application;

    abstract class HtmlBase implements \Techy\Lib\Core\I\IHtmlView {
        const
            VIEW_DIR = 'views/',
            TEMPLATE_EXT = '.tpl',
            TEMPLATE_DIR = 'templates/',
            LAYOUT_DIR = 'layouts/',

            DEFAULT_TEMPLATE = 'empty',
            DEFAULT_LAYOUT = 'index';

        protected
            $ajax,

            $template,
            $layout,

            $globals = array(),
            $json = array(),
            $page = array(),
            $data = array(),

            $pathTemplate = '',
            $pathLayout = '';

        public function template( $name ){
            $this->template = $name;
        }

        public function getTemplate(){
            return $this->template;
        }

        public function layout( $name ){
            $this->layout = $name;
        }

        public function globals( $name, $value ){
            $this->globals[$name] = $value;
        }

        public function json( $name, $value ){
            $this->json[$name] = $value;
        }

        public function page( $name, $value = null ){
            if( is_null( $value ))
                return isset( $this->page[$name] ) ? $this->page[$name] : null;

            return $this->page[$name] = $value;
        }

        public function set( $name, $value = null ){
            if( is_array( $name )) {
                $this->data += $name;
            }else{
                $this->data[$name] = $value;
            }
            return $this;
        }

        public function setData($array){
            $this->data = $array;
        }

        /**
         * Render with layout template or yield template only
         *
         * @param bool $layoutName
         * @return string
         * @throws \UnexpectedValueException
         */
        public function render( $layoutName = false ){
            if(!$this->template ) {
                $this->template = static::DEFAULT_TEMPLATE;
            }

            $viewDir = Application::instance()->getViewDir();

            if(!$this->pathTemplate ){
                $this->pathTemplate = $viewDir . static::TEMPLATE_DIR;
                if(!file_exists( $this->pathTemplate . $this->template . static::TEMPLATE_EXT )) {
                    throw new \UnexpectedValueException( 'Template '. $this->template .' not exists' );
                }
            }

            if( !$layoutName ){
                if(!$this->layout ) {
                    $this->layout = static::DEFAULT_LAYOUT;
                }
            } else {
                //$this->pathLayout = '';
                $this->layout = $layoutName;
            }
            $this->pathLayout = $viewDir . static::LAYOUT_DIR;
            if(!file_exists( $this->pathLayout . $this->layout . static::TEMPLATE_EXT )) {
                $this->pathLayout = '';
            }

            return $this->html();
        }

        /**
         * @abstract
         * @return string
         */
        abstract protected function html();
    }
	