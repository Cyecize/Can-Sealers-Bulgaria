<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 3:09 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Notification;
use AppBundle\Entity\User;

interface NotificationService
{
    /**
     * @param Notification $notification
     */
    public function seeNotification(Notification $notification): void;

    /**
     * @param Notification $notification
     */
    public function removeOne(Notification $notification): void;

    /**
     * @param Notification[] $notifications
     */
    public function removeAll(array $notifications): void;

    /**
     * @param User $user
     * @param string $content
     * @param string $href
     * @return Notification
     */
    public function sendNotification(User $user, string $content, string $href): Notification;

    /**
     * @param int $id
     * @return Notification|null
     */
    public function findOneById(int $id): ?Notification;

    /**
     * @param User $user
     * @return Notification[]
     */
    public function findByUser(User $user): array;

    /**
     * @param User $user
     * @return Notification[]
     */
    public function findNotSeenByUser(User $user): array;

}