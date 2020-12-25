<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 12:13 PM
 */

namespace App\Service;


interface MailSendingService
{
    /**
     * @param string $subject
     * @param string $message
     * @param string $receiver
     */
    public function sendText(string $subject, string $message, string $receiver) : void ;

    /**
     * @param string $subject
     * @param $content
     * @param string $receiver
     */
    public function sendHtml(string $subject, $content, string $receiver) : void ;
}