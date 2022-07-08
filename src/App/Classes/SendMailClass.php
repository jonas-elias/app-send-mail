<?php

namespace Src\App\Classes;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Src\Config\MailConfig;

class SendMailClass extends AttributesMailClass
{
    public function __construct(AttributesMailClass $attributes)
    {
        $this->to = $attributes->to;
        $this->subject = $attributes->subject;
        $this->message = $attributes->message;
    }

    public function sendMail()
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                                    //Enable verbose debug output
            $mail->isSMTP();                                                            //Send using SMTP
            $mail->Host       = MailConfig::$service;                               //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                                   //Enable SMTP authentication
            $mail->Username   = MailConfig::$emailName;                                   //SMTP username
            $mail->Password   = MailConfig::$emailPassword;                                             //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                          //Enable implicit TLS encryption
            $mail->Port       = MailConfig::$port;                                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(MailConfig::$emailName, 'Remetente estudo');
            $mail->addAddress($this->to);                                               //Add a recipient
            //$mail->addAddress('ellen@example.com');                                   //Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');                             //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');                        //Optional name

            //Content
            $mail->isHTML(true);                                                        //Set email format to HTML
            $mail->Subject =  $this->subject;
            $mail->Body    =  $this->message;
            $mail->AltBody = 'É necessário um cliente que suporte HTML5';

            $mail->send();

            $this->status['status_cod'] = 200;

            return $this->status['status_cod'];
        } catch (Exception $e) {
            return $this->status['status_cod'] = $e->getCode();
        }
    }
}
