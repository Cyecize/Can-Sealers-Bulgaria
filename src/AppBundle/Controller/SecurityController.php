<?php

namespace AppBundle\Controller;

use AppBundle\BindingModel\UserRegisterBindingModel;
use AppBundle\Constants\Config;
use AppBundle\Constants\Roles;
use AppBundle\Entity\User;
use AppBundle\Form\UserRegisterType;
use AppBundle\Service\FirstRunService;
use AppBundle\Service\LanguageDbService;
use AppBundle\Service\LogService;
use AppBundle\Service\NotificationSendingService;
use AppBundle\Service\RoleService;
use AppBundle\Utils\ModelMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{

    private const LOGGER_LOCATION = "Security Controller";

    /**
     * @Route("/login", name="security_login" )
     * @Security("is_anonymous()", message="You are already logged in")
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loginAction(AuthenticationUtils $authUtils, Request $request)
    {
        $lastUsername = null;
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        if ($error != null) {
            $error = $this->dictionary->invalidPassword();
            $repo = $this->getDoctrine()->getRepository(User::class);
            $existingUser = $repo->findByUsernameOrEmail($lastUsername);
            if ($existingUser == null) {
                $lastUsername = null;
                $error = $this->dictionary->usernameOrEmailDoesNotExist();
            }
        }

        $queryName = $request->get('u');
        if($queryName != null) $lastUsername = $queryName;

        return $this->render(":security:login.html.twig",
            array(
                "last_username" => $lastUsername,
                "error" => $error,
            ));
    }

    /**
     * @Route("/register", name="security_register" )
     * @Security("is_anonymous()", message="You are already logged in")
     * @param Request $request
     * @param ModelMapper $modelMapper
     * @param RoleService $roleService
     * @param FirstRunService $firstRunService
     * @param LogService $logService
     * @param NotificationSendingService $notificationSendingService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, ModelMapper $modelMapper, RoleService $roleService, FirstRunService $firstRunService,
                                   LogService $logService, NotificationSendingService $notificationSendingService)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $bindingModel = new UserRegisterBindingModel();
        $userForm = $this->createForm(UserRegisterType::class, $bindingModel);
        $userForm->handleRequest($request);
        $error = null;

        if ($userForm->isSubmitted()) {
            if (count($this->validate($bindingModel)) > 0)
                goto  escape;

            $userInDb = $userRepo->findOneBy(array('username' => $bindingModel->getUsername()));
            if ($userInDb != null) {
                $error = $this->dictionary->usernameAlreadyTaken();
                $bindingModel->setUsername("");
                goto escape;
            }

            $userInDbByEmail = $userRepo->findOneBy(array('email' => $bindingModel->getEmail()));
            if ($userInDbByEmail != null) {
                $error = $this->dictionary->emailAlreadyInUse();
                $bindingModel->setEmail("");
                goto  escape;
            }

            $user = $modelMapper->map($bindingModel, User::class);
            $user->setPassword($this->get('security.password_encoder')->encodePassword($user, $user->getPassword()));

            if (count($roleService->findAll()) < 1) {
                $firstRunService->initDb();
                $user->addRole($roleService->findByRoleName(Roles::ROLE_GOD));
                $user->addRole($roleService->findByRoleName(Roles::ROLE_ADMIN));
            }
            $user->addRole($roleService->findByRoleName(Roles::ROLE_USER));
            $user->setLanguage($this->language->findLanguageByName($bindingModel->getLocale()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $notificationSendingService->onUserRegister($user);
            $logService->log(self::LOGGER_LOCATION, sprintf("User with username %s was created", $user->getUsername()));

            return $this->redirectToRoute("security_login", ['u'=>$user->getUsername()]);
        }

        escape:

        return $this->render(":security:register.html.twig", array(
            "bindingModel" => $bindingModel,
            'form1' => $userForm->createView(),
            'langs'=> [Config::COOKIE_BG_LANG, Config::COOKIE_EN_LANG],
            'error' => $error,
        ));

    }

    /**
     * This is the route the user can use to logout.
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     * @Route("/logout/", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}
