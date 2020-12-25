<?php

namespace App\Service;

use App\Constants\Roles;
use App\Entity\Question;
use App\Entity\User;
use App\Service\Lang\ILanguagePack;

class NotificationSendingServiceImpl implements NotificationSendingService
{
    private const ON_USER_REGISTER_FORMAT = "%s се регистрира.";
    private const ON_USER_REGISTER_HREF_FORMAT = "/user/show/%s";
    private const ON_MESSAGE_SENT_FORMAT = "Имате нов въпрос";
    private const ON_MESSAGE_SENT_HREF_FORMAT = "/admin/questions/view/%s";

    /**
     * @var NotificationService
     */
    private $notificationDbService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var ILanguagePack
     */
    private $lang;


    public function __construct(NotificationService $notificationDbService,
                                UserService $userService,
                                LocalLanguage $localLanguage)
    {
        $this->notificationDbService = $notificationDbService;
        $this->userService = $userService;
        $this->lang = $localLanguage->dictionary();
    }


    /**
     * @param User $user
     */
    public function onUserRegister(User $user): void
    {
        $targetUsers = $this->userService->findByRole(Roles::ROLE_ADMIN);
        $msg = sprintf(self::ON_USER_REGISTER_FORMAT, $user->getUsername());
        $link = sprintf(self::ON_USER_REGISTER_HREF_FORMAT, $user->getUsername());
        foreach ($targetUsers as $targetUser) {
            $this->notificationDbService->sendNotification($targetUser, $msg, $link);
        }
    }

    /**
     * @param Question $question
     */
    public function onQuestion(Question $question): void
    {
        $msg = self::ON_MESSAGE_SENT_FORMAT;
        $href = sprintf(self::ON_MESSAGE_SENT_HREF_FORMAT, $question->getId());
        $admins = $this->userService->findByRole(Roles::ROLE_ADMIN);
        foreach ($admins as $admin) {
            $this->notificationDbService->sendNotification($admin, $msg, $href);
        }
    }

    /**
     * @param string $message
     * @param string $href
     */
    public function notifyAll(string $message, string $href): void
    {
        $users = $this->userService->findAll();
        foreach ($users as $user) {
            $this->notificationDbService->sendNotification($user, $message, $href);
        }
    }
}