<?php
    namespace Techy\Script\Cron\Queues;

    use Techy\Config\Mail;
    use Techy\Lib\Core\Mail\Mailer;
    use Techy\Lib\Core\System\Application;
    use Techy\Lib\Core\Utilities\Logger;
    use Techy\Lib\Core\View\Native;
    use Techy\Script\Cron\Queues\QueueBase;

    class MailerScript extends QueueBase {

        protected static $queueName = 'MailQueue';

        /**
         * @var \Techy\Lib\Core\Mail\Mailer
         */
        private $Mailer;

        public function __construct(){
            $this->Mailer = Mailer::get( Mail::get( 'default' ));
        }

        protected function runQueue( array $item ){
            $View = new Native();
            $View->layout('empty');
            $View->template( 'mails/'. $item['template'] );
            $View->set( $item['data'] );
            if(!$this->Mailer->send( $item['email'], $item['subject'], $View->render( false ))){
                $this->repush();
                Application::instance()->getLogger()->log('mail-errors',
                    $this->Mailer->lastError() ."\n". print_r( $item, true )
                );
            }
        }
    }
