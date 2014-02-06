<?php
    namespace Techy\Lib\Core\Data;

    use \Techy\Lib\Core\Data\I as I;

    abstract class AbstractObject implements I\IObject {

        /**
         * @var array
         */
        private
            $data = array(),
            $fields = array(),
            $validators = array(),
            $filters = array(),
            $errors = array(),
            $errorsPrefix = ''
        ;

        final public function __construct(){
            $this->init();
            foreach( $this->fields as $offset => $Field )
                $this->data[$offset] = null;
        }

        /**
         * Init fields with this method
         */
        abstract protected function init();

        /**
         * Overwrite this method to validate data
         */
        protected function customValidation(){}

        final public function offsetExists( $offset ){
            return array_key_exists( $offset, $this->fields );
        }

        final public function offsetGet( $offset ){
            if( array_key_exists( $offset, $this->fields ))
                return $this->data[$offset];

            return null;
        }

        final public function offsetSet( $offset, $value ){
            if( array_key_exists( $offset, $this->fields ))
                $this->data[$offset] = $this->offsetValue( $offset, $value );
        }

        final public function offsetUnset( $offset ){
            if( array_key_exists( $offset, $this->fields ))
                $this->data[$offset] = null;
        }

        final public function offsetValue( $offset, $value ){
            if( array_key_exists( $offset, $this->fields )){
                /**
                 * @var AbstractField $Field
                 * @var AbstractFilter $Filter
                 */
                $Field = $this->fields[$offset];
                if( isset( $this->filters[$offset] )){
                    $Filter = $this->filters[$offset];
                    $value = $Filter->filter( $value );
                }
                return $Field->set( $value );
            }
            return null;
        }

        final public function validate(){
            $this->errors = array();
            foreach( $this->validators as $offset => $validators ){
                foreach( $validators as $Validator ){
                    /**
                     * @var AbstractValidator $Validator
                     */
                    if(!$Validator->validate( $this->data[$offset] ))
                        $this->addValidationError( $offset, $this->errorsPrefix . $Validator->getMessage());
                }
            }
            $this->customValidation();
            return empty( $this->errors );
        }

        /**
         * @param array $a
         * @return $this
         */
        final public function fetch( array $a ){
            $this->preFetch();
            foreach( $this->fields as $offset => $Field )
                $this->data[$offset] = $this->offsetValue( $offset, isset( $a[$offset] ) ? $a[$offset] : null );
            $this->postFetch();
            return $this;
        }

        /**
         * @param array $a
         * @return $this
         */
        final public function merge( array $a ){
            foreach( $a as $offset => $value )
                $this->offsetSet( $offset, $value );
            return $this;
        }

        /**
         * @param array $a
         * @return array
         */
        public function fieldsFilter( array $a ){
            $result = array();
            foreach( $this->fields as $offset => $Field )
                $result[$offset] = $this->offsetValue( $offset, isset( $a[$offset] ) ? $a[$offset] : null );
            return $result;
        }

        /**
         * @return array
         */
        final public function export(){
            return $this->data;
        }

        protected function preFetch(){}
        protected function postFetch(){}

        /**
         * @return array
         */
        final public function keys(){
            return array_keys( $this->fields );
        }

        /**
         * @return array
         */
        final public function getValidationErrors(){
            return $this->errors;
        }

        /**
         * @param $offset
         * @return AbstractField
         * @throws \UnexpectedValueException
         */
        final protected function getField( $offset ){
            if( array_key_exists( $offset, $this->fields ))
                return $this->fields[$offset];

            throw new \UnexpectedValueException();
        }

        /**
         * @param string $prefix
         * @return $this
         */
        final protected function setErrorPrefix( $prefix ){
            $this->errorsPrefix = $prefix;
            return $this;
        }

        /**
         * @param string $offset
         * @param string $type
         * @param array $options
         * @return $this
         * @throws \UnexpectedValueException
         */
        final protected function addField( $offset, $type, array $options = array()){
            if( array_key_exists( $offset, $this->fields ))
                throw new \UnexpectedValueException( 'Field "'. $offset .'" has been added already' );

            $this->fields[$offset] = AbstractField::create( $type, $options );
            return $this;
        }

        /**
         * @param string $offset
         * @param string $type
         * @param string $message
         * @param array $options
         * @return $this
         * @throws \UnexpectedValueException
         */
        final protected function addValidator( $offset, $type, $message, array $options = array()){
            if(!array_key_exists( $offset, $this->fields ))
                throw new \UnexpectedValueException( 'There is no field "'. $offset .'" to add a validator for '. get_class( $this ));

            if(!isset( $this->validators[$offset] ))
                $this->validators[$offset] = array();

            $this->validators[$offset][] = AbstractValidator::create( $type, $message, $options );
            return $this;
        }

        /**
         * @param string $offset
         * @param string $type
         * @param array $options
         * @return $this
         * @throws \UnexpectedValueException
         */
        final protected function addFilter( $offset, $type, array $options = array()){
            if(!array_key_exists( $offset, $this->fields ))
                throw new \UnexpectedValueException( 'There is no field "'. $offset .'" to add a filter for '. get_class( $this ));

            if( array_key_exists( $offset, $this->filters ))
                throw new \UnexpectedValueException( 'Filter for field "'. $offset .'" has been added already' );

            $this->filters[$offset] = AbstractFilter::create( $type, $options );
            return $this;
        }

        /**
         * @param $offset
         * @param $message
         * @return $this
         */
        final protected function addValidationError( $offset, $message ){
            if(!isset( $this->errors[$offset] ))
                $this->errors[$offset] = array();

            $this->errors[$offset][] = $message;
            return $this;
        }
    }
