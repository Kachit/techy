<?php
    namespace Techy\Lib\Object;

    use Techy\Lib\Core\Data;
    use Techy\Lib\Core\System\Application;

    class MailQueue extends Data\QueueObject {

        protected static $queueName = 'mail';

        protected function init(){
            $this
                ->addField( 'email', Data\AbstractField::FIELD_STRING )
                ->addField( 'subject', Data\AbstractField::FIELD_STRING )
                ->addField( 'template', Data\AbstractField::FIELD_STRING )
                ->addField( 'data', Data\AbstractField::FIELD_ARRAY )
            ;
        }
    }
