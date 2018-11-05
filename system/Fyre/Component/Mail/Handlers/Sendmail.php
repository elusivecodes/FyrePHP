<?php

namespace Fyre\Component\Mail\Driver;

use
    Fyre\Component\Mail\MailHandler;

use function
    implode,
    ini_set,
    mail;

class Sendmail extends MailHandler
{

    public function __construct(MailConfig &$config)
    {
        parent::__construct($config);

        ini_set('sendmail_from', $this->config->from);
        ini_set('sendmail_path', $this->config->path);
    }

    protected function _send($email, string $boundary): bool
    {
        return mail(
            implode(
                ', ',
                $email['to']
            ),
            $email['subject'],
            $this->prepBody($email, $boundary),
            $this->prepHeaders($email, $boundary, false)
        );
    }

}
