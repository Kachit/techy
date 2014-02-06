<?php
    namespace Techy\Script;

    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\System\SwitchDispatcher;
    use Techy\Lib\Core\Utilities\Directory;

    class ConfigureDevScript extends Base {

        /**
         * @var \Blitz $Blitz
         */
        protected $Blitz;

        protected
            $hosts,
            $newHosts;

        public function run(){
            Directory::instance()->createRecursive( CONFIG_NGINX_DIR );

            $this->hosts = file_get_contents( '/etc/hosts' );
            $this->Blitz = new \Blitz();
            $this->Blitz->load( file_get_contents( CONFIG_DIR .'dev/nginx.conf.tpl' ));

            $Dispatcher  = SwitchDispatcher::instance();
            $Application = Application::instance();
            $Application->setDispatcher( $Dispatcher );
            $DirectoryIterator = new \DirectoryIterator( APPLICATION_DIR );
            foreach( $DirectoryIterator as $Current ){
                /**
                 * @var \DirectoryIterator $Current
                 */
                if( $Current->isDot() || !$Current->isDir())
                    continue;

                $Dispatcher->setApplicationName( $Current->getBasename());
                $this->configureApplication( $Application );
            }

            echo PHP_EOL . PHP_EOL
                .'Logs directories generated:' . PHP_EOL . PHP_EOL
                .'You should add the lines below to your nginx.conf, then reload nginx:' . PHP_EOL
                .'include '. CONFIG_NGINX_DIR .'*;' . PHP_EOL . PHP_EOL;

            if( $this->newHosts ){
                echo 'Also you have to add this line to your /etc/hosts file:' . PHP_EOL
                    .'127.0.0.1'."\t". implode( ' ', $this->newHosts ) . PHP_EOL . PHP_EOL;
            }
        }

        protected function configureApplication( Application $Application ){
            $appLogsDir = LOGS_DIR . $Application->getName();
            $appName = $Application->getName();
            $appDomain = mb_strtolower( $appName );
            $appHost = $appDomain .'.'. get_current_user() .'.int';

            Directory::instance()->createRecursive( $appLogsDir .'/dev' );
            Directory::instance()->createRecursive( $appLogsDir .'/test' );

            $this->Blitz->set( array(
                'root' => ROOT,
                'host' => $appHost,
                'app_name' => $appName,
            ));
            $nginxConfig = $this->Blitz->parse();

            $File = new \SplFileObject( CONFIG_NGINX_DIR . $appDomain, 'w' );
            $File->fwrite( $nginxConfig );

            if( false === strpos( $this->hosts, $appHost ))
                $this->newHosts[] = $appHost;
        }
    }
