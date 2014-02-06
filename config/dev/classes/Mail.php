<?php
    namespace Techy\Config;

    class Mail extends \Techy\Lib\Core\Mail\Config {
        protected static $settings = array(
            'default' => array(
                'type'   => 'testMail',
                'site'   =>	'Techy',
                'from'   =>	'Techy',
                'sender' =>	'info@techy.com',
            ),
        );
    }
