<?php
    namespace Techy\Lib\Core\Utilities;

    class DynamicConfigFile {
        /**
         * @var DynamicConfigFile
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return DynamicConfigFile
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @param $name
         * @param array $config
         * @param string $path
         */
        public function write( $name, array $config, $path = CONFIG_DYNAMIC_DIR ){
            $File = new \SplFileObject(  $path . $name .'.php', 'w' );

            $File->fwrite( '<?php' . PHP_EOL . PHP_EOL . '$config = array (' );
            $this->recursiveWrite( $File, $config );
            $File->fwrite( PHP_EOL . ');' );
        }

        private function recursiveWrite( \SplFileObject $File, array $array, $level = 1 ){
            $pad = "\n". str_repeat( "\t", $level );
            foreach( $array as $key => $element ){
                $line = $pad;
                if( is_numeric( $key )){
                    $line .= $key .' => ';
                }else{
                    $line .= '\''. $key .'\' => ';
                }
                if( is_numeric( $element )){
                    $line .= $element;
                }
                elseif( is_bool( $element )){
                    $line .= $element ? 'true' : 'false';
                }
                elseif( is_string( $element )){
                    $line .= '\''. str_replace( '\'', '\\\'', $element ) .'\'';
                }
                elseif( is_array( $element )){
                    $File->fwrite( $line .'array(' );
                    $this->recursiveWrite( $File, $element, $level + 1 );
                    $line = $pad .')';
                }
                else
                    continue;

                $line .= ',';

                $File->fwrite( $line );
            }
        }

        /**
         * @param $name
         * @param string $path
         * @return array
         * @throws \UnexpectedValueException
         */
        public function read( $name, $path = CONFIG_DYNAMIC_DIR ){
            $config = array();
            $file = $path . $name .'.php';
            if(!file_exists( $file ))
                throw new \UnexpectedValueException( 'Dynamic file "'. $file .'" is not exist' );
            include $file;
            return $config;
        }
    }