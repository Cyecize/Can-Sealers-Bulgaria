<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/29/2018
 * Time: 4:49 PM
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\User;
use AppBundle\Service\LocalLanguage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class LoginListener
{
    /**
     * @var LocalLanguage
     */
    private $localLanguage;

    /**
     * @var User
     */
    private $user;

    public function __construct(LocalLanguage $localLanguage, TokenStorage $tokenStorage)
    {
        $this->localLanguage = $localLanguage;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function onLogin(){
        $this->localLanguage->setLang($this->user->getLanguage()->getLocaleName());
    }
}