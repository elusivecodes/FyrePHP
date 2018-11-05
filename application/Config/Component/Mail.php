<?php

namespace Config\Component;

use
    Fyre\Component\Mail\MailConfig;

class Mail extends MailConfig
{
    public $handler = "\Fyre\Component\Mail\Handlers\SMTP";
}
