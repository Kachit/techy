<?php
    namespace Techy\Config;

    use Techy\Lib\Core\Database;
    use Techy\Lib\Core\NoSQL\Redis;
    use Techy\Lib\Core\System;
    use Techy\Lib\Core\Utilities\Date;

    class Registry extends System\RegistryConfig {

        public function Session(){
            $Application = System\Application::instance();
            return System\Session::instance(
                $Application->getRequest(),
                $Application->getResponse()
            );
        }

        public function Redis(){
            return Redis::instance();
        }

        public function DatabaseConnection(){
            return Database\Connection::instance();
        }

        public function DatabaseSpotConnection(){
            return Database\SpotConnection::instance();
        }

        public function Date(){
            return Date::instance();
        }
    }
