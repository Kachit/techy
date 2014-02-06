<?php
    namespace Techy\Lib\Core\Utilities\Upload;

    use Techy\Lib\Core\Exception\UploadError;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\Utilities\Directory;

    class LocalFile implements \Techy\Lib\Core\Utilities\I\IFileUploader {
        /**
         * @var string
         */
        protected $uploadedFile;

        /**
         * @return array
         * @throws \Techy\Lib\Core\Exception\FieldValidation
         * @throws \InvalidArgumentException
         */
        public function fetch(){
            $args = func_get_args();
            if(!count( $args ))
                throw new \InvalidArgumentException( __CLASS__.'::'.__METHOD__.' have to pass at least 1 argument: field name ' );

            $fieldName = array_shift( $args );
            if(!isset( $_FILES[$fieldName] ))
                UploadError::throwError( UploadError::NOT_EMPTY );

            $field = $_FILES[$fieldName];

            $tmp   = $field['tmp_name'];
            $type  = $field['type'];
            $error = $field['error'];
            $size  = $field['size'];
            try {
                while( $key = array_shift( $args )){
                    $tmp   = $tmp[$key];
                    $type  = $type[$key];
                    $error = $error[$key];
                    $size  = $size[$key];
                }
            }
            catch( \Exception $E ){
                Application::instance()->getLogger()->logException( $E );
                UploadError::throwError( UploadError::UNKNOWN );
            }
            if( $error || !$size ){
                Application::instance()->getLogger()->log('upload', $error );
                UploadError::throwError( UploadError::UNKNOWN );
            }

            $this->uploadedFile = $tmp;

            return $type;
        }

        /**
         * @return string
         */
        public function getTempPath(){
            return $this->uploadedFile;
        }

        /**
         * @param string $filePath
         */
        public function upload( $filePath ){
            // create directory for uploaded file
            Directory::instance()->createRecursive( preg_replace( '#/[^/]+$#', '', $filePath ));

            if(!move_uploaded_file( $this->uploadedFile, $filePath )){
                Application::instance()->getLogger()->log('upload', 'move_uploaded_file fail');
                UploadError::throwError( UploadError::UNKNOWN );
            }
            exec('chmod 775 '. $filePath );
        }
    }
