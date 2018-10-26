<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 12:22 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\Roles;
use AppBundle\Entity\PasswordRecovery;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;

class MailingServiceImpl implements MailingService
{
    private const LOGGER_LOCATION = "Mailing Service";

    /**
     * @var MailSendingService
     */
    private $mailerService;

    /**
     * @var LogService
     */
    private $logger;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var LocalLanguage
     */
    private $language;

    /**
     * @var UserService
     */
    private $userService;


    public function __construct(MailSendingService $mailerService, \Twig_Environment $twig, LocalLanguage $language, UserService $userService, LogService $logger)
    {
        $this->mailerService = $mailerService;
        $this->twig = $twig;
        $this->language = $language;
        $this->userService = $userService;
        $this->logger = $logger;
    }

    public function sendQuestionToAdmins(Question $question): void
    {
        try {
            $admins = $this->userService->findByRole(Roles::ROLE_ADMIN);
            $html = $this->twig->render('mailing/new-question.html.twig', [
                'question' => $question,
                'user' => $question->getUser()
            ]);
            $summary = $question->createSummary();
            foreach ($admins as $admin) {
                $this->mailerService->sendHtml($summary, $html, $admin->getEmail());
            }
            $this->logger->log(self::LOGGER_LOCATION, sprintf("Sent email to %s admins", count($admins)));
        }catch (\Exception $ex){
            $this->logger->log(self::LOGGER_LOCATION, sprintf("Error while sending emails: %s" ,$ex->getMessage()));
        }
    }

    public function sendMessagePasswordRecovery(PasswordRecovery $passwordRecovery, User $user): void
    {
        $content = $this->twig->render('mailing/password-recovery.html.twig', [
            'user' => $user,
            'passwordRecovery' => $passwordRecovery
        ]);
        $this->mailerService->sendHtml($this->language->dictionary()->forgottenPassword(), $content, $user->getEmail());
    }

}