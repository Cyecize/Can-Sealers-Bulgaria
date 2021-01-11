<?php

namespace App\Controller;

use App\BindingModel\UserRegisterBindingModel;
use App\Constants\Config;
use App\Constants\Roles;
use App\Entity\User;
use App\Form\UserRegisterType;
use App\Service\FirstRunService;
use App\Service\LocalLanguage;
use App\Service\LogService;
use App\Service\NotificationSendingService;
use App\Service\RoleService;
use App\Utils\ModelMapper;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends BaseController
{
    private $validator;

    private $encoder;

    public function __construct(LocalLanguage $language,
                                ValidatorInterface $validator,
                                UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($language);
        $this->validator = $validator;
        $this->encoder = $encoder;
    }

    private const LOGGER_LOCATION = "Security Controller";

    /**
     * @Route("/login", name="security_login" )
     * @Security("is_anonymous()", message="You are already logged in")
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws NonUniqueResultException
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

        return $this->render("security/login.html.twig",
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
     * @return Response
     */
    public function registerAction(Request $request,
                                   ModelMapper $modelMapper,
                                   RoleService $roleService,
                                   FirstRunService $firstRunService,
                                   LogService $logService,
                                   NotificationSendingService $notificationSendingService)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $bindingModel = new UserRegisterBindingModel();
        $userForm = $this->createForm(UserRegisterType::class, $bindingModel);
        $userForm->handleRequest($request);
        $error = null;

        if ($userForm->isSubmitted()) {
            if (count($this->validator->validate($bindingModel)) > 0)
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
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));

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
            $logService->log(self::LOGGER_LOCATION,
                sprintf("User with username %s was created", $user->getUsername())
            );

            return $this->redirectToRoute("security_login", ['u'=>$user->getUsername()]);
        }

        escape:

        return $this->render("security/register.html.twig", array(
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
