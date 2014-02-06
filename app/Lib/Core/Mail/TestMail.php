<?php
	namespace Techy\Lib\Core\Mail;

    use Techy\Lib\Core\System\Application;

    class TestMail extends Mailer {

        public function send( $to, $subject, $content ){
            Application::instance()->getLogger()->log(
                'mail',
                '-----------------------------------------------'.
                "\nSender: ". $this->Config->sender .
                "\nFrom: ". $this->Config->from .
                "\nSubject: ". $subject .
                "\nTo: ". $to .
                "\nBody:\n". $content .
                "\n\n"
            );
            return true;
        }

        public function lastError(){
            return '';
        }
    }
	