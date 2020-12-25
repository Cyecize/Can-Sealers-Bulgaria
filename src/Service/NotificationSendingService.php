<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 3:47 PM
 */

namespace App\Service;


use App\Entity\Question;
use App\Entity\User;

interface NotificationSendingService
{
    /**
     * @param User $user
     */
    public function onUserRegister(User $user): void;

    /**
     * @param Question $question
     */
    public function onQuestion(Question $question) : void ;

    /**
     * @param string $message
     * @param string $href
     */
    public function notifyAll(string $message, string $href): void;

}