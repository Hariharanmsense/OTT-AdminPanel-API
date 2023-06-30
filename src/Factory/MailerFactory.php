<?php

declare(strict_types=1);

namespace App\Factory;

use PHPMailer\PHPMailer\PHPMailer;
use App\Exception\Mailer\MailerException;

final class MailerFactory
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function createMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);

        // Server settings
        //$mail->SMTPDebug = 1;
        $mail->isSMTP();
        $mail->Host = $this->settings['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $this->settings['username'];
        $mail->Password = $this->settings['password'];
        $mail->SMTPSecure = $this->settings['SMTPSecure'];
        $mail->Port = (int)$this->settings['port'];
        $mail->SetFrom($this->settings['frommail'], $this->settings['fromname']);
		$mail->AddReplyTo($this->settings['frommail'],  $this->settings['fromname']);
        $mail->IsHTML(true);

        return $mail;
    }
	
	    public function SendEmail($toEmailId, $toEmailName, $subject, $body, $objLogger){
		try{
            $objLogger->info("======= Start Mailler ================");
            $objLogger->info("-- toEmailId : ".$toEmailId);
            $objLogger->info("-- toEmailName : ".$toEmailName);
            $objLogger->info("-- subject : ".$subject);

            $mail = $this->createMailer();
		    $mail->addAddress($toEmailId, $toEmailName);
            $mail->Subject = $subject;
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!";  
			$mail->MsgHTML($body);
			//$mail->MsgHTML('test message');
			if($mail->Send()) 
			{
                $mailstatus = "SUCCESS";
			}
			else
			{
                $mailstatus = "Mailer Error: " . $mail->ErrorInfo;
			}

            $objLogger->info("-- mailstatus : ".$mailstatus);

			return $mailstatus;
		}
       catch (Exception $ex) {

        $objLogger->error("Error Code : ".$ex->getCode()."Error Message : ".$ex->getMessage());
        $objLogger->error("Error File : ".$ex->getFile()."Error Line : ".$ex->getLine());
        //$objLogger->error("Error Trace String : ".$ex->getTraceAsString());
        $objLogger->info("======= End Mailler ================");
        if(!empty($ex->getMessage())){
            throw new MailerException($ex->getMessage(), $ex->getCode());
        }
        else {
            throw new MailerException('Email Send Failed', 401);
        }

       }
   }
}

?>