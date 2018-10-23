<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 12:19 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Question;

interface MailingService
{
    /**
     * @param Question $question
     */
    public function sendQuestionToAdmins(Question $question) : void;
}