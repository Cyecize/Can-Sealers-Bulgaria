<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/29/2018
 * Time: 4:49 PM
 */

namespace App\EventListener;


use App\Service\LocalLanguage;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class LoginListener
{
    /**
     * @var LocalLanguage
     */
    private $localLanguage;

    public function __construct(LocalLanguage $localLanguage)
    {
        $this->localLanguage = $localLanguage;
    }

    /**
     * Registered in services.yaml.
     * @param InteractiveLoginEvent $event
     */
    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        $this->localLanguage->setLang($user->getLanguage()->getLocaleName());
    }
}