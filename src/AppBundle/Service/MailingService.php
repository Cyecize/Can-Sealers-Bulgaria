<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 12:19 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\PasswordRecovery;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;

interface MailingService
{
    /**
     * @param Question $question
     */
    public function sendQuestionToAdmins(Question $question) : void;

    /**
     * @param PasswordRecovery $passwordRecovery
     * @param User $user
     */
    public function sendMessagePasswordRecovery(PasswordRecovery $passwordRecovery, User $user) : void ;
}