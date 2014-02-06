<?php
    namespace Techy\Lib\Core\Data\Field;

	use Techy\Lib\Core\Data\AbstractField;
    use Techy\Lib\Core\Data\I\IExportable;
    use Techy\Lib\Core\Data\I\IObject;

    class FieldObject extends AbstractField {
        /**
         * @var string
         */
        private $className;

        /**
         * @param $value
         * @return null|string
         * @throws \UnexpectedValueException
         */
        public function set( $value ){
            if( is_string( $value ))
                $value = unserialize( $value );

            if( is_null( $value ))
                return null;

            elseif( $value instanceof IExportable )
                $value = $value->export();

            if(!is_array( $value ))
                return null;

            /**
             * @var IObject $Value
             */
            $Value = new $this->className();
            $Value->fetch( $value );
            return $Value->export();
        }

        protected function setClass( $className ){
            $className = '\\Techy\\Lib\\Object\\'. $className;
            if(!class_exists( $className, true ))
                throw new \UnexpectedValueException( 'Class "'. $className .'" not implemented' );

            $this->className = $className;
        }
    }
