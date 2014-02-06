<?php
    namespace Techy\Lib\Object\User;

    use Techy\Lib\Core\Data;
    use Techy\Lib\Core\System\Registry;
    use Techy\Lib\Module\Acl\AclSettings;
    use Techy\Lib\Service\User\AuthService;

    class AuthUserObject extends Data\DatabaseObject {
        const
            PASSWORD_SALT = 'oCV&OSGpa;oent'
        ;

        protected function getService(){
            if(!$this->Service )
                $this->Service = new AuthService( 'main', 'users' );

            return $this->Service;
        }

        protected function init(){
            $now = Registry::instance()->Date()->timestamp();
            $this
                ->addField( 'user_id', Data\AbstractField::FIELD_INT )
                ->setPrimary( 'user_id' )

                ->addField( 'email', Data\AbstractField::FIELD_STRING )
                ->addFilter( 'email', Data\AbstractFilter::FILTER_LOWERCASE_TRIM )
                ->addValidator( 'email', Data\AbstractValidator::VALIDATOR_NOT_EMPTY, 'auth.forms.email.notEmpty' )
                ->addValidator( 'email', Data\AbstractValidator::VALIDATOR_EMAIL, 'auth.forms.email.email' )

                ->addField( 'password', Data\AbstractField::FIELD_STRING )
                ->addFilter( 'password', Data\AbstractFilter::FILTER_TRIM )
                ->addValidator( 'password', Data\AbstractValidator::VALIDATOR_NOT_EMPTY, 'auth.forms.password.notEmpty' )

                ->addField( 'created', Data\AbstractField::FIELD_DATE_TIME, array( 'default' => $now ))
                ->addField( 'updated', Data\AbstractField::FIELD_DATE_TIME, array( 'default' => $now ))
                ->addField( 'deleted', Data\AbstractField::FIELD_DATE_TIME, array( 'default' => 0 ))
            ;
        }

        /**
         * @param $email
         * @param $password
         * @return bool
         */
        public function loadByEmail( $email, $password = false ){
            $params = array( 'email' => $this->offsetValue( 'email', $email ));
            if( $password )
                $params['password'] = $this->passwordHash( $this->offsetValue( 'password', $password ));

            $data = $this->getService()->load( $params );
            if(!$data )
                return false;

            $this->fetchLoadedData( reset( $data ));
            return true;
        }

        /**
         * @return bool
         */
        public function isReal(){
            return !!$this['user_id'];
        }

        public function is( $group ){
            return $this->isReal() && $group === $this['group_id'];
        }

        public function can( $action ){
            if(!$this->isReal())
                return false;

            return in_array( $action, AclSettings::groupPrivileges( $this['group_id'] ));
        }

        public function begin(){
            $this->getService()->begin();
        }

        public function commit(){
            $this->getService()->commit();
        }

        public function rollback(){
            $this->getService()->rollback();
        }

        /**
         * @param string $password
         * @return string
         */
        public static function passwordHash( $password ){
            return sha1( self::PASSWORD_SALT . sha1( $password ));
        }

        /**
         * @return string
         */
        public static function generatePassword(){
            $alphabet = 'abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
            $password = '';

            $i = 10;
            $l = strlen( $alphabet ) - 1;
            while( $i-- )
                $password .= substr( $alphabet, mt_rand( 0, $l ), 1 );

            return $password;
        }

        /**
         * Returns non-hashed password
         *
         * @return string
         */
        public function hashPassword(){
            $password = $this['password'];
            $this['password'] = self::passwordHash( $password );
            return $password;
        }

        /**
         * @param $password
         * @return bool
         */
        public function checkPassword( $password ){
            return self::passwordHash( $password ) === $this['password'];
        }

        /**
         * @return array
         */
        public function getActivationData(){
            $data = $this->getService()->activationData( array(
                'user_id' => $this->getId(),
                'code' => sha1(uniqid('activation')),
            ));
            if(!$data )
                return false;
            return reset( $data );
        }

        /**
         * @return array
         */
        public function getPasswordRecoveryData(){
            $data = $this->getService()->recoveryData( array(
                'user_id' => $this->getId(),
                'code' => sha1(uniqid('recovery')),
                'created' => Registry::instance()->Date()->timestamp(),
            ));
            if(!$data )
                return false;
            return reset( $data );
        }

        /**
         * @param $code
         * @return bool
         */
        public function checkPasswordRecoveryCode( $code ){
            $data = $this->getService()->checkRecoveryCode( array(
                'user_id' => $this->getId(),
                'code' => $code,
            ));
            if(!$data )
                return false;
            return intval( reset( $data ));
        }
    }
