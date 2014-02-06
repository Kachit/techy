<?php

namespace Techy\Lib\Core\Exception {
    use Techy\Lib\Core\System\Application;

    /**
     * Message will be shown to user
     */
    class Client extends \Exception{}

    /**
     * Message will be shown to user as json with result = error
     */
    class Api extends \Exception{}

    /**
     * 404 Page not found and others
     * Specific message will be shown to user
     */
    class Page extends \Exception{}

    /**
     * Logging user ip
     */
    class HackAttempt extends \InvalidArgumentException {}

    class e401 extends Page {
        public function __construct( $message = 'Unauthorized', $code = 401, \Exception $previous = null ){
            parent::__construct( $message, $code, $previous );
            Application::instance()->getLogger()->log( 'error-401', $message );
        }
    }

    class e404 extends Page {
        public function __construct( $message = 'Not Found', $code = 404, \Exception $previous = null ){
            parent::__construct( $message, $code, $previous );
            Application::instance()->getLogger()->log( 'error-404', $message );
        }
    }

    class e500 extends \Exception {
        public function __construct( $message = 'Internal Server Error', $code = 500, \Exception $previous = null ){
            parent::__construct( $message, $code, $previous );
            Application::instance()->getLogger()->log( 'error-500', $message );
        }
    }

    /**
     * Invalid request
     * Logging as HackAttempt
     */
    class Request extends HackAttempt{}

    /**
     * Unauthorized access attempt
     * Maybe in cause of automatic logout
     * Redirect
     */
    class Authorize extends \Exception{}

    /**
     * Validation error
     * Error message looks like "validationType"
     */
    class FieldValidation extends \Exception{}

    /**
     * Validation error
     * Error message is a json array of validation errors
     * array (
     *     'fieldName' => '{prefix}fieldName.validationType',
     *     ...
     * )
     */
    class RowValidation extends \Exception{}

    /**
     * Validation error with custom message
     */
    class CustomError extends \Exception{}

    /**
     * Curl error
     */
    class CurlError extends \Exception{}

    /**
     * Error on webdav action
     */
    class WebDav extends \Exception{}

    /**
     * Exception on wrong format
     */
    class WrongFormat extends \Exception{}

    /**
     * Exception for queue restart
     */
    class QueueError extends \Exception{}
}

namespace Techy\Lib\Core\Exception\Database {

    /**
     * Error in query logic
     * Wrong arguments, error in template, etc.
     */
    class QueryLogicError extends \ErrorException {}

    /**
     * Connection error
     */
    class Connection extends \Exception {}

    /**
     * Error in SQL query
     */
    class SQLQuery extends \Exception {}

    /**
     * Slow query signal
     */
    class SlowQuery extends \Exception {}
}
