<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail as MimeTemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{

    public function __construct(private MailerInterface $mailer)
    {
    }
    public function sendMail($clientMail, $qr, $code): void
    {
        $email = (new Email())
            ->from(new Address('no-reply@wamidu.com', 'WAMIDU'))
            ->to($clientMail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Wamidu Qr Code !')
            ->html('<header><img src="public/assets/img/logo.png"  alt="wamidu"/></header>
            <div>
            <h4>Salut Dionel,</h4>
            <p>Félicitations ! WAMIDU vous à ajouté à ses clients .</p>
            <div>Voici votre QrCode qui vous permettra de faire des commandes</br>
            <img src="assets/img/logo.png"  alt="wamidu" width="60" height="60"/>
            <p> Votre code personnel est :  111111</p>
             </div>
            </div>
            <footer><a><img src="assets/img/logo.png"  alt="wamidu"  width="60" height="60"/>WAMIDU</a>
            </br>
            <p> Copyright 2023 WAMIDU</p>
            
            <footer>');
        //$this->$mailer->send($email);
    }
}