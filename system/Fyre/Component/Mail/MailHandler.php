<?php

namespace Fyre\Component\Mail;

use const
    FILTER_VALIDATE_EMAIL;

use function
    array_filter,
    array_map,
    base64_encode,
    chunk_split,
    explode,
    filter_var,
    implode,
    is_array,
    md5,
    microtime,
    strip_tags,
    trim,
    uniqid;

abstract class MailHandler
{
    protected $config;
    protected $mail = [];

    public function __construct(MailConfig &$config)
    {
        $this->config = &$config;

        $this->reset();
    }

    public function bcc($bcc): self
    {
        $this->mail['bcc'] = $this->parseEmails($bcc);

        return $this;
    }

    public function cc($cc): self
    {
        $this->mail['cc'] = $this->parseEmails($cc);

        return $this;
    }

    public function from(string $from, ?string $name = null, ?string $returnPath = null): self
    {
        $this->mail['from'] = $this->parseEmail($from);
        $this->mail['name'] = $name;

        return $this;
    }

    public function message(string $body): self
    {
        $this->mail['message'] = $body;

        return $this;
    }

    public function messagePlain(string $body): self
    {
        $this->mail['messagePlain'] = $body;

        return $this;
    }

    public function replyTo(string $replyTo, ?string $name = null): self
    {
        $this->mail['replyTo'] = $this->parseEmail($replyTo);
        $this->mail['replyToName'] = $name;

        return $this;
    }

    public function reset(): self
    {
        $this->mail = [
            'from'  => '',
            'fromName' => null,
            'replyTo' => null,
            'replyToName' => null,
            'returnPath' => null,
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'subject' => '',
            'message' => '',
            'messagePlain' => ''
        ];

        return $this;
    }

    public function send(): self
    {
        return $this->_send(
            $this->mail,
            md5(uniqid().microtime())
        );
    }

    public function subject(string $subject): self
    {
        $this->mail['subject'] = $subject;

        return $this;
    }

    public function to($to): self
    {
        $this->mail['to'] = $this->parseEmails($to);

        return $this;
    }

    protected function prepBody($email, string $boundary)
    {
        $body = [];

        $body[] = '--'.$boundary;
        $body[] = 'Content-type: text/plain; charset=ISO-8859-1';
        $body[] = 'Content-Transfer-Encoding: base64';
        $body[] = chunk_split(
            base64_encode(
                strip_tags(
                    $email['messagePlain'] ?
                        $email['messagePlain'] :
                        $email['message']
                )
            )
        );

        $body[] = '--'.$boundary;
        $body[] = 'Content-type: text/html; charset=ISO-8859-1';
        $body[] = 'Content-Transfer-Encoding: base64';
        $body[] = chunk_split(
            base64_encode(
                $email['message']
            )
        );

        return implode("\r\n", $body);
    }

    protected function prepHeaders($email, string $boundary)
    {
        $headers = [];

        $headers[] = 'From: '.$this->prepEmail($email['from'], $email['fromName']);

        if ($email['replyTo']) {
            $headers[] = 'Reply-To: '.$this->prepEmail($email['replyTo'], $email['replyToName']);
        }

        if ($email['returnPath']) {
            $header[] = 'Return-Path: '.$email['returnPath'];
        }

        if ( ! empty($email['cc'])) {
            $headers[] = 'Cc: '.implode(', ', $email['cc']);
        }

        if ( ! empty($email['bcc'])) {
            $headers[] = 'Bcc: '.implode(', ', $email['bcc']);
        }

        $headers[] = 'MIME-Version: 1.0';

        $headers[] = 'Content-type: multipart/alternative; boundary="'.$boundary.'"';

        return implode("\r\n", $headers)."\r\n";
    }

    private function prepEmail(string $email, ?string $name): string
    {
        return ($name ? $name.' \<'.$email.'\>' : $email);
    }

    private function parseEmails($emails): array
    {
        return array_filter(
            array_map(
                function($email) {
                    return $this->parseEmail($email);
                },
                is_array($emails) ?
                    $emails :
                    explode(',', $emails)
            ),
            function ($email) {
                return !! $email;
            }
        );
    }

    private function parseEmail($email): string
    {
        return filter_var(
            trim($email),
            FILTER_VALIDATE_EMAIL
        );
    }

}
