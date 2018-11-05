<?php

namespace Fyre\Component\Mail;

interface MailHandlerInterface
{

    //public function attach(string $file);
    //public function attachmentId(string $file);
    public function bcc($bcc);
    public function cc($cc);
    public function from(string $from, ?string $name = null, ?string $returnPath = null);
    public function message(string $body);
    public function messagePlain(string $body);
    public function replyTo(string $replyTo, ?string $name = null);
    public function reset();
    public function send();
    public function subject(string $subject);
    public function to($to);

}
