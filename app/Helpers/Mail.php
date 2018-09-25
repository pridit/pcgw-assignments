<?php

namespace App\Helpers;

class Mail
{
    function __construct($config)
    {
        $this->config = $config;
        $this->transport = (new \Swift_SmtpTransport($this->config->get('mail.host')))
            ->setPort($this->config->get('mail.port'))
            ->setUsername($this->config->get('mail.username'))
            ->setPassword($this->config->get('mail.password'))
            ->setEncryption($this->config->get('mail.encryption'));
    }

    function doNew($data)
    {
        $mailer = new \Swift_Mailer($this->transport);

        $message = (new \Swift_Message($data['subject']))
            ->setFrom([$this->config->get('mail.from.address') => $this->config->get('mail.from.name')])
            ->setTo($data['to'])
            ->setBody($data['message'], 'text/html');

        return $mailer->send($message);
    }
}
