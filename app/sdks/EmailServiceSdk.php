<?php

namespace App\sdks;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailServiceSdk
{
    const API_ROUT = "email_service";

    const API_ENTRYPOINT = "/entry";

    protected Request $request;

    protected string $managerAddress;

    protected string $noReplyAddress;

    protected PHPMailer $mailer;

    function __construct(Request $request){
        $this->managerAddress = config("services.email.manager_address");
        $this->noReplyAddress = config("services.email.no_reply_address");;
        $this->request = $request;
        $mailer = new PHPMailer(true);
    }

    /**
     * @throws Exception
     */
    public function sendMessage(string $subject, string $body, string|array $address, $isHtml = false): void
    {
        $this->mailer->isSMTP();                                      // Set mailer to use SMTP
        $this->mailer->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
        $this->mailer->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mailer->Username = 'user@example.com';                 // SMTP username
        $this->mailer->Password = 'secret';                           // SMTP password
        $this->mailer->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $this->mailer->From = $this->noReplyAddress;
        $this->mailer->FromName = 'Mailer';
        if (is_string($address)) {
            $address = [$address];
        }
        foreach ($address as $addressItem) {
            if (is_array($addressItem)) {
                $this->mailer->addAddress($addressItem[0], $addressItem[1]);               // Name is optional
            } else {
                $this->mailer->addAddress($addressItem[0]);
            }
        }
        $this->mailer->addReplyTo($this->managerAddress, 'Manager');

        $this->mailer->WordWrap = 50;


        $this->mailer->Subject = $subject;
        $this->mailer->isHTML($isHtml);
        if ($isHtml) {
            $this->mailer->AltBody = $body;
        } else {
            $this->mailer->Body = $body;
        }

        if(!$this->mailer->send()) {
            throw new Exception('Mailer Error: ' . $this->mailer->ErrorInfo);
        }
    }

    public function sendEchoMessage(string $text) {
        // todo: make it later
    }

}
