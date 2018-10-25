<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/27/2018
 * Time: 11:02 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\ChangePasswordBindingModel;
use AppBundle\Entity\ProductCategory;
use AppBundle\Entity\User;
use AppBundle\Exception\InternalRestException;
use AppBundle\Exception\NotFoundException;
use AppBundle\Form\EditPasswordType;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\MailingService;
use AppBundle\Service\PasswordRecoveryService;
use AppBundle\Service\UserService;
use AppBundle\Utils\ModelMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;


class PasswordRecoveryController extends BaseController
{
    private const INVALID_PASSWORD_TOKEN = "Invalid Password Token";
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var PasswordRecoveryService
     */
    private $passwordRecoveryDbManager;

    /**
     * @var MailingService
     */
    private $mailingService;

    public function __construct(LocalLanguage $language, UserService $userDb, PasswordRecoveryService $passwordRecoveryDb, MailingService $mailing)
    {
        parent::__construct($language);
        $this->userService = $userDb;
        $this->passwordRecoveryDbManager = $passwordRecoveryDb;
        $this->mailingService = $mailing;
    }

    /**
     * @Route("/users/password/forgotten", name="forgotten_password")
     * @Security("is_anonymous()", message="You are already logged in")
     */
    public function forgotPasswordAction()
    {
        return $this->render('security/forgotten-password.html.twig', [
            'error' => null
        ]);
    }

    /**
     * @Route("/users/password/recover/find", name="find_me",methods={"POST"})
     * @Security("is_anonymous()", message="You are already logged in")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findMeAction(Request $request)
    {
        $this->validateToken($request);
        $err = null;
        $userOrEmail = $request->get('username');
        if ($userOrEmail == null) $userOrEmail = "";
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->findByUsernameOrEmail($userOrEmail);
        if ($user == null)
            return $this->render('security/forgotten-password.html.twig', ['error' => sprintf($this->dictionary->userNotFoundFormat(), $userOrEmail)]);
        return $this->redirectToRoute('recover_password', ['username' => $user->getUsername()]);
    }

    /**
     * @Route("/users/password/recover/{username}", name="recover_password", defaults={"username"=null})
     * @Security("is_anonymous()", message="You are already logged in")
     * @param Request $request
     * @param $username
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     * @throws \AppBundle\Exception\RestException
     */
    public function sendPasswordResetEmailAction(Request $request, $username, \Swift_Mailer $mailer)
    {
        $user = $this->userService->findOneByUsername($username);
        if ($user == null)
            throw new NotFoundException(sprintf($this->dictionary->userNotFoundFormat(), $username));

        if ($request->getMethod() == 'POST') {
            $this->validateToken($request);
            $passRec = $this->passwordRecoveryDbManager->addPasswordRecovery($user);
            $this->mailingService->sendMessagePasswordRecovery($passRec, $user);
            try {
                $spool = $mailer->getTransport()->getSpool();
                $transport = $this->get('swiftmailer.transport.real');
                $spool->flushQueue($transport);
                return $this->render('security/request-was-send.html.twig');
            } catch (\Exception $exception) {

            }
        }
        return $this->render('security/send-reset-request.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/users/password/reset", name="reset_password")
     * @Security("is_anonymous()", message="You are already logged in")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestException
     * @throws \AppBundle\Exception\IllegalArgumentException
     */
    public function resetPasswordAction(Request $request)
    {
        $username = $request->get('username');
        $token = $request->get('token-pass');

        if ($username == null || $token == null)
            throw new InternalRestException($this->dictionary->fieldCannotBeNull());
        $user = $this->userService->findOneByUsername($username);
        if ($user == null) throw new InternalRestException(sprintf($this->dictionary->userNotFoundFormat(), $username));
        $passwordRecovery = $this->passwordRecoveryDbManager->findOneByUser($user);
        if ($passwordRecovery == null)
            throw new InternalRestException(self::INVALID_PASSWORD_TOKEN);
        if ($token != $passwordRecovery->getToken())
            throw new InternalRestException(self::INVALID_PASSWORD_TOKEN);

        $bindingModel = new ChangePasswordBindingModel();
        $form = $this->createForm(EditPasswordType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validate($bindingModel)) > 0)
                goto escape;
            $this->userService->changePassword($user, $bindingModel, false);
            $this->passwordRecoveryDbManager->removePasswordRecovery($passwordRecovery);
            return $this->redirectToRoute('security_login');
        }

        escape:
        return $this->render('security/password-reset.html.twig', [
            'form1' => $form->createView()
        ]);
    }
}