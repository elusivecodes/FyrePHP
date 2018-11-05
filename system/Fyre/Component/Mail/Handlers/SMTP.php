<?php

namespace Fyre\Component\Mail\Driver;

use
    Fyre\Component\Mail\MailConfig,
    Fyre\Component\Mail\MailHandler;

use function
    array_key_exists,
    base64_encode,
    fclose,
    fwrite,
    mb_strlen,
    stream_context_create,
    stream_socket_client,
    stream_socket_enable_crypto;

class SMTP extends MailHandler
{
    private $sock;

    protected function _send($email, string $boundary)
    {
        if ( ! $this->sock) {
            $this->connect();
            $this->authenticate();
        }

        $this->sendCommand('from', $email['from']);
        $this->sendCommand('to', $email['to']);
        $this->sendCommand('data');

        $this->sendData(
            $this->prepHeaders($email, $boundary)
        );

        $this->sendData(
            $this->prepBody($email, $boundary)
        );

        if ($this->config->keepAlive) {
            $this->sendCommand('reset');
        } else {
            $this->sendCommand('quit');
        }
    }

    private function authenticate(): bool
    {
        if ( ! $this->config->auth) {
            return true;
        }

        $this->sendData('AUTH LOGIN');
        $reply = $this->getSMTPData();

        if (strpos($reply, '503') === 0) {
            return true;
        }

        if (strpos($reply, '334') !== 0) {
            SMTPException::authFailed();
        }

        $this->sendData(
            base64_encode($this->config->user)
        );
        $reply = $this->getSMTPData();

        if (strpos($reply, '334') !== 0) {
            SMTPException::authUsernameFailed();
        }

        $this->sendData(
            base64_encode($this->config->password)
        );
        $reply = $this->getSMTPData();

        if (strpos($reply, '235') !== 0) {
            SMTPException::authPasswordFailed();
        }

        if ($this->config->keepAlive) {
            $this->config->auth = false;
        }

        return true;
    }

    private function connect(): bool
    {
        $this->sock = stream_socket_client(
            $this->config->host.':'.$this->config->port,
            $errno,
            $errstr,
            10,
            STREAM_CLIENT_CONNECT,
            stream_context_create(
                array (
                    'ssl' => array (
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    ) 
                )
            )
        );

        if ( ! $this->sock) {
            SMTPException::noSocket();
        }

        stream_set_timeout($this->sock, 5);

        $welcome = $this->getSMTPData();

        if ($this->config->crypt === 'tls') {
            $this->sendCommand('hello');
            $this->sendCommand('starttls');
            stream_set_blocking($this->sock, true);
            $crypto = stream_socket_enable_crypto(
                $this->sock,
                true,
                STREAM_CRYPTO_METHOD_TLS_CLIENT
            );
            stream_set_blocking($this->sock, false);
        }

        return $this->sendCommand('hello');
    }

    private function end(): bool
    {
        return $this->sendCommand($this->config->keepAlive ? 'reset' : 'quit');
    }

    public function getSMTPData(): string
    {
        $data = '';
        while (($str = fgets($this->sock, 512)) !== false) {
            $data .= $str;
        }

        return $data;
    }

    public function getHostname(): string
    {
        if (array_key_exists('SERVER_NAME', $_SERVER)) {
			return $_SERVER['SERVER_NAME'];
        }

        return array_key_exists('SERVER_ADDR', $_SERVER) ?
            '['.$_SERVER['SERVER_ADDR'].']' :
            '[127.0.0.1]';
    }

    private function sendCommand($command, $data = ''): bool
    {
        if ($command === 'hello') {
            if ($this->config->auth) {
                $this->sendData('EHLO '.$this->getHostname());
            } else {
                $this->sendData('HELO '.$this->getHostname());
            }
            $response = 250;
        } else if ($command === 'starttls') {
            $this->sendData('STARTTLS');
            $response = 220;
        } else if ($command === 'from') {
            $this->sendData('MAIL FROM:<'.$data.'>');
            $response = 250;
        } else if ($command === 'to') {
            if ($this->config->dsn) {
                $this->sendData('RCPT TO:<'.$data.'> NOITFY=SUCCESS,DELAY,FAILURE ORCPT=rfc822;'.$data);
            } else {
                $this->sendData('RCPT TO:<'.$data.'>');
            }
            $response = 250;
        } else if ($command === 'data') {
            $this->sendData('DATA');
            $response = 354;
        } else if ($command === 'reset') {
            $this->sendData('RSET');
            $response = 250;
        } else if ($command === 'quit') {
            $this->sendData('QUIT');
            $response = 221;
        } else {
            // unknown command
        }

        $reply = $this->getSMTPData();

        if (strpos($reply, (string) $response) !== 0) {
            SMTPException::invalidResponse();
        }

        if ($command === 'quit') {
            fclose($this->sock);
        }

        return true;
    }

    public function sendData($data): bool
    {
        $data .= "\r\n";
        $length = mb_strlen($data);
        $written = 0;
        while ($written < $length) {
            if (($result = fwrite($this->sock, substr($data, $written))) === false) {
                break;
            }

            $written += $result;
        }

        if ($result === false) {
            SMTPException::sendDataFailed();
        }

        return true;
    }

}
