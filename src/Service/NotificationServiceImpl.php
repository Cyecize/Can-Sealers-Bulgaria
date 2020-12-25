<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 3:10 PM
 */

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use App\Exception\InternalRestException;
use App\Exception\RestException;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class NotificationServiceImpl implements NotificationService
{
    private const EMPTY_NOTIFICATION_MSG = "Notification was empty, cannot send empty notification!";
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var NotificationRepository|ObjectRepository
     */
    private $notificationRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->notificationRepo = $this->entityManager->getRepository(Notification::class);
    }

    /**
     * @param Notification $notification
     */
    public function seeNotification(Notification $notification): void
    {
        if($notification->getIsSeen())
            return;
        $notification->setIsSeen(true);
        $this->edit($notification);
    }

    /**
     * @param Notification $notification
     */
    public function removeOne(Notification $notification): void
    {
        $this->entityManager->remove($notification);
        $this->entityManager->flush();
    }

    /**
     * @param Notification[] $notifications
     */
    public function removeAll(array $notifications): void
    {
        foreach ($notifications as $notification) {
            $this->entityManager->remove($notification);
        }
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @param string $content
     * @param string $href
     * @return Notification
     * @throws RestException
     */
    public function sendNotification(User $user, string $content, string $href): Notification
    {
        if ($user == null || $content == null || $href == null)
            throw new InternalRestException(self::EMPTY_NOTIFICATION_MSG);
        $notification = new Notification();
        $notification->setContent($content);
        $notification->setUser($user);
        $notification->setHref($href);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
        return $notification;
    }

    /**
     * @param int $id
     * @return Notification|null
     */
    public function findOneById(int $id): ?Notification
    {
        return $this->notificationRepo->findOneBy(array('id' => $id));
    }

    /**
     * @param User $user
     * @return Notification[]
     */
    public function findByUser(User $user): array
    {
        return $this->notificationRepo->findBy(array('user' => $user), array('id' => 'DESC'));
    }

    /**
     * @param User $user
     * @return Notification[]
     */
    public function findNotSeenByUser(User $user): array
    {
        return $this->notificationRepo->findBy(array('user' => $user, 'isSeen' => false), array('id' => 'DESC'));
    }

    //PRIVATE
    private function edit(Notification $notification)
    {
        $this->entityManager->merge($notification);
        $this->entityManager->flush();
    }
}