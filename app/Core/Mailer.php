<?php

declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

final class Mailer
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $config = Config::get('app.mail', []);

        $this->mailer->isSMTP();
        $this->mailer->Host = $config['host'] ?? 'smtp.mailtrap.io';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'] ?? '';
        $this->mailer->Password = $config['password'] ?? '';
        $this->mailer->SMTPSecure = $config['encryption'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = (int) ($config['port'] ?? 587);
        $this->mailer->setFrom($config['from_address'] ?? 'no-reply@mediai.local', $config['from_name'] ?? 'MediAI');
        $this->mailer->isHTML(true);
    }

    public function send(string $to, string $subject, string $template): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAllRecipients();
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $template;
            $this->mailer->AltBody = strip_tags($template);
            return $this->mailer->send();
        } catch (Exception $exception) {
            Logger::getInstance()->error('Mail send failed: ' . $exception->getMessage());
            return false;
        }
    }
}
