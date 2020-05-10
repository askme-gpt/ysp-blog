<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Yaf\Controller_Abstract as Controller;

/**
 * 各种工具类快速调用，发邮件，验证码，图片上传
 */
class ToolController extends Controller
{

    /**
     * 上传图片，simplemd上传图片使用
     * @return [type] [description]
     */
    public function uploadImageAction()
    {
        $option = [
            'path' => APPLICATION_PATH . '/public/upload/',
        ];
        $upload = new Upload($option);
        if (!($file_name = $upload->uploadFile('upload_file'))) {
            echo $upload->errorInfo;
        }
        echo json_encode(['file_path' => '/public/upload/' . basename($file_name)]);
    }

    /**
     * 发送邮件，$addresses, $cc, $bcc, $attachment, $subject, $body
     * @param  [type] $option [description]
     * @return [type]         [description]
     */
    public function sendMailAction($option)
    {
        $mail       = new PHPMailer(true);
        $mailConfig = Yaf\Registry::get("config")->get("mail")->toarray();

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
            $mail->isSMTP(); // Send using SMTP
            $mail->Host       = $mailConfig['host']; // Set the SMTP server to send through
            $mail->SMTPAuth   = true; // Enable SMTP authentication
            $mail->Username   = $mailConfig['username']; // SMTP username
            $mail->Password   = $mailConfig['password']; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable SSL encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = $mailConfig['port']; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($mailConfig['setFrom'], 'Mailer');

            if ($option['addresses']) {
                if (is_array($option['addresses'])) {
                    foreach ($option['addresses'] as $key => $value) {
                        $mail->addAddress($value);
                    }
                } else {
                    $mail->addAddress($option['addresses']);
                }
            }

            $mail->addReplyTo($mailConfig['addReplyTo'], 'Information');

            if ($option['cc']) {
                if (is_array($option['cc'])) {
                    foreach ($option['cc'] as $key => $value) {
                        $mail->addCC($value);
                    }
                } else {
                    $mail->addCC($option['cc']);
                }
            }

            if ($option['bcc']) {
                if (is_array($option['bcc'])) {
                    foreach ($option['bcc'] as $key => $value) {
                        $mail->addBCC($value);
                    }
                } else {
                    $mail->addBCC($option['bcc']);
                }
            }

            if ($option['attachment']) {
                // Optional name
                $mail->addAttachment($option['attachment']['path'], $option['attachment']['name']);
            }
            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $option['subject'];
            $mail->Body    = $option['body'];
            $mail->AltBody = '<h1>没有邮件内容！</h1>';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
