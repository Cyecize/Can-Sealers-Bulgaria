<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 12:14 PM
 */

namespace App\Service;


use App\Constants\Config;
use Swift_TransportException;

class MailSendingServiceImpl implements MailSendingService
{
    private const LOG_LOCATION = "MailSender";
    private const MAIL_FAIL_FORMAT = "Mail failed!, subject: %s  receiver: %s";

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var LogService
     */
    private $logger;

    /**
     * @var string
     */
    private $senderEmail;

    /**
     * @var string
     */
    private $from;

    public function __construct(\Swift_Mailer $mailer, LogService $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->senderEmail = $_ENV[Config::ENV_MAILER_USER];
        $this->from = $_ENV[Config::ENV_MAILER_SENDER_NAME];
    }

    /**
     * @param string $subject
     * @param string $message
     * @param string $receiver
     */
    public function sendText(string $subject, string $message, string $receiver): void
    {
        $swiftMailerMsg = (new \Swift_Message($subject))
            ->setFrom([$this->senderEmail => $this->from])
            ->setTo($receiver)
            ->setBody($message);
        try {
            $this->mailer->send($swiftMailerMsg);
        } catch (Swift_TransportException $e) {
            $this->logger->log(self::LOG_LOCATION, sprintf(self::MAIL_FAIL_FORMAT, $subject, $receiver));
        }
    }

    /**
     * @param string $subject
     * @param $content
     * @param string $receiver
     */
    public function sendHtml(string $subject, $content, string $receiver): void
    {
        $swiftMailerMsg = (new \Swift_Message($subject))
            ->setFrom([$this->senderEmail=> $this->from])
            ->setTo($receiver)
            ->setBody($content, 'text/html');
        try {
            $this->mailer->send($swiftMailerMsg);
        } catch (Swift_TransportException $e) {
            $this->logger->log(self::LOG_LOCATION, sprintf(self::MAIL_FAIL_FORMAT, $subject, $receiver));
        }
    }
}