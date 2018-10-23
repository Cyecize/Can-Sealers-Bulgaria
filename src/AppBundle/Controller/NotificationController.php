<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Notification;
use AppBundle\Exception\InternalRestException;
use AppBundle\Exception\RestException;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\NotificationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotificationController
 * @package AppBundle\Controller
 */
class NotificationController extends BaseController
{
    private const USER_NOT_LOGGED_IN = "User is not logged in!";
    private const INVALID_NOTIFICATION_MSG = "Invalid Notification";
    /**
     * @var NotificationService
     */
    private $notificationDbService;

    public function __construct(LocalLanguage $language, NotificationService $notificationDb)
    {
        parent::__construct($language);
        $this->notificationDbService = $notificationDb;
    }

    /**
     * @Route("/notifications/mobile", name="notifications_mobile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     */
    public function mobileNotificationsAction()
    {
        return $this->render('default/notifications.html.twig', [
            'notis' => $this->notificationDbService->findByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/notifications/request", name="update_notifications", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws RestException
     */
    public function updateNotificationsAction(Request $request)
    {
        $this->validateToken($request);
        return $this->renderMyNotifications();
    }

    /**
     * @Route("/notifications/remove-all", name="notifications_remove_all")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws RestException
     */
    public function removeAllNotificationsAction(Request $request)
    {
        $this->validateToken($request);
        $this->notificationDbService->removeAll($this->notificationDbService->findByUser($this->getUser()));
        return $this->renderMyNotifications();
    }

    /**
     * @Route("/notifications/view/{notiId}", name="notification_view", defaults={"notiId"=null}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws RestException
     */
    public function viewNotification(Request $request, $notiId)
    {
        $this->validateToken($request);
        $noti = $this->notificationDbService->findOneById($notiId);
        $this->validateNotification($noti);
        $this->notificationDbService->seeNotification($noti);
        return $this->renderMyNotifications();
    }

    /**
     * @Route("/notifications/remove/{notiId}", name="notification_remove", defaults={"notiId"=null}, methods={"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $notiId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws RestException
     */
    public function removeNotification(Request $request, $notiId)
    {
        $this->validateToken($request);
        $noti = $this->notificationDbService->findOneById($notiId);
        $this->validateNotification($noti);
        $this->notificationDbService->removeOne($noti);
        return $this->renderMyNotifications();
    }

    //PRIVATE LOGIC

    /**
     * @param Notification|null $noti
     * @throws RestException
     */
    private function validateNotification(Notification $noti = null)
    {
        if ($noti == null || $noti->getUser()->getId() != $this->getUserId())
            throw new InternalRestException(self::INVALID_NOTIFICATION_MSG);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function renderMyNotifications()
    {
        return $this->render('partials/notifications/notification-update-result.html.twig', [
            'notis' => $this->notificationDbService->findByUser($this->getUser()),
        ]);
    }
}