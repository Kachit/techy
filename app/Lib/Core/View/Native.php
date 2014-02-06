<?php
    namespace Techy\Lib\Core\View;

    use Techy\Lib\Core\System\Application;

    class Native extends HtmlBase {
        const TEMPLATE_EXT = '.phtml';

        public function html(){
            global $globals, $inject, $d, $p, $I18n;
            $d = $this->data;
            $p = $this->page;
            $globals = $this->pathLayout ? $this->globals : $this->json;
            $I18n = Application::instance()->getI18n();


            $closure = function( $path, $yield = '' ) use( $d, $p, $globals){
                /**
                 * shortcuts
                 */
                $viewDir = Application::instance()->getViewDir();
                $inject = function( $template ) use( $viewDir ){
                    $path = $viewDir . Native::TEMPLATE_DIR . $template . Native::TEMPLATE_EXT;
                    if( file_exists( $path ) )
                        return $path;
                    throw new \Exception('Template "'.$path.'" not found ');
                };

                ob_start();
                try {
                    include $path;
                    $result = ob_get_clean();
                }
                catch( \Exception $e ){
                    ob_end_clean();
                    throw $e;
                }
                return $result;
            };
            $yield = $closure( $this->pathTemplate . $this->template . self::TEMPLATE_EXT );

            if( $this->pathLayout )
                $yield = $closure( $this->pathLayout . $this->layout . self::TEMPLATE_EXT, $yield );

            return $yield;
        }

    }
