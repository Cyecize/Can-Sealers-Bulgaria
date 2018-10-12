<?php
/**
 * Created by PhpStorm.
 * User: Cyecize
 * Date: 12/4/2017
 * Time: 5:11 PM
 */

namespace AppBundle\Controller;

use AppBundle\Constants\Constants;
use AppBundle\Entity\User;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\UserService;
use AppBundle\Utils\RandomCreator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swift_TransportException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends BaseController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(LocalLanguage $language, UserService $userService)
    {
        parent::__construct($language);
        $this->userService = $userService;
    }

    /**
     * @Route("/profile", name="my_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function myProfileAction(Request $request)
    {
        return $this->render(":users:profile-page.html.twig",
            [
                'error' => $request->get('error'),
                'info' => $request->get('info'),
            ]);
    }

    /**
     * @Route("/edit-password", name="edit_password")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editPasswordAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());

        $userPasswords = new UserPasswords();
        $form = $this->createForm(UserPasswordType::class, $userPasswords);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            $oldPass = $userPasswords->getOldPassword();
            if (!$userPasswords->isPasswordsValid())
                return $this->redirectToRoute('my_profile', array('error' => 'Попълнете всички полета!'));
            if (!password_verify($oldPass, $user->getPassword()))
                return $this->redirectToRoute('my_profile', array('error' => 'Невалидна стара парола!'));
            if (!$userPasswords->isPasswordsEqual())
                return $this->redirectToRoute('my_profile', array('error' => 'Паролите не съвпадат'));

            $newPassword = $encoder->encodePassword($user, $userPasswords->getNewPassword());
            $user->setPassword($newPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("security_logout");
        }

        return $this->render(":Queries:password-edit.html.twig",
            [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/edit-personal", name="edit_personal_info")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editPersonalInfoAction(Request $request)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
        $editedUser = new User();
        $form = $this->createForm(UserPersonalInfoType::class, $editedUser);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user->setPhoneNumber($editedUser->getPhoneNumber());
            $user->setFullName($editedUser->getFullName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($user);
            $entityManager->flush();

            return $this->redirectToRoute("my_profile");
        }

        return $this->render(":Queries:personal-info-edit.html.twig",
            [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/remove-user/{username}", name="remove_user", defaults={"username"=null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function removeUserAction(Request $request)
    {
        if (!$this->getUser()->getAdminStatus()) {
            return $this->redirectToRoute("homepage");
        }
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        $users = $userRepo->findAll();
        $userToRemove = $request->get('userToRemove');

        if ($userToRemove != null) {
            $targetUser = $userRepo->findOneBy(array('username' => $userToRemove));

            if ($targetUser == null)
                $targetUser = $userRepo->findOneBy(array('email' => $userToRemove));
            if ($targetUser == null)
                return $this->redirectToRoute('my_profile', ['error' => "Невалидно потр. име или E-Mail"]);
            if ($targetUser->getAdminStatus())
                return $this->redirectToRoute("my_profile", ['error' => "Опитахте се да изтриете администраторски акаунт"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($targetUser);
            $entityManager->flush();

            return $this->redirectToRoute("my_profile");
        }

        return $this->render(':Queries:user-remove.html.twig',
            [
                'users' => $users,
            ]);
    }

    /**
     * @Route("/grant-user", name="grant_user_admin_status")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function grantUserAction(Request $request)
    {
        if (!$this->getUser()->getAdminStatus()) {
            return $this->redirectToRoute("homepage");
        }
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        $users = $userRepo->findAll();
        $userToGrant = $request->get('userToRemove');

        if ($userToGrant != null) {
            $targetUser = $userRepo->findOneBy(array('username' => $userToGrant));

            if ($targetUser == null)
                $targetUser = $userRepo->findOneBy(array('email' => $userToGrant));
            if ($targetUser == null)
                return $this->redirectToRoute('my_profile', ['error' => "Невалидно потр. име или E-Mail"]);

            $entityManager = $this->getDoctrine()->getManager();
            $targetUser->setAdminStatus(true);
            $entityManager->persist($targetUser);
            $entityManager->flush();

            return $this->redirectToRoute("my_profile");
        }

        return $this->render(":Queries:user-grant-admin.html.twig",
            [
                'users' => $users,
            ]);
    }

    /**
     * @Route("/recover-password", name="password_recovery")
     *
     **/
    public function recoverPasswordAction(Request $request)
    {


        return $this->render(":default:recover-password.html.twig",
            [
                'information' => $this->manageInformation(),
            ]);
    }

    /**
     * @Route("/search-user-for-recovery/{username}", name="search_user_for_recovery", defaults={"username"=null})
     **/
    public function searchUserAction(Request $request, $username, \Swift_Mailer $mailer)
    {
        $user = null;
        $errorMessage = null;
        if ($username == null) {
            $errorMessage = "Невалидно потр. име или E-Mail";
            goto  escape;
        }
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->findOneBy(array('username' => $username));
        if ($user == null)
            $user = $userRepo->findOneBy(array('email' => $username));
        if ($user == null) {
            $errorMessage = "Несъществуващо потр. име или E-Mail";
            goto  escape;
        }

        //send code section
        $userToSend = $request->get('targetUser');
        if ($userToSend != null) {
            $passwordRecovery = new PasswordRecovery();
            $passwordRecovery->setUserId($user->getId());
            $passwordRecovery->setCode(RandomCreator::createSecretCode());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($passwordRecovery);
            $entityManager->flush();

            //send mail
            $message = (new \Swift_Message("Zatvarachki -> забравена парола"))
                ->setFrom([Constants::$mailer => Constants::$mailerAs])
                ->setTo($user->getEmail())
                ->setBody($this->renderView(
                    ':Mailing:passwor-code-mail.html.twig',
                    array('user' => $user, 'passForm' => $passwordRecovery)
                ),
                    'text/html');

            try {
                $mailer->send($message);
            } catch (Swift_TransportException $e) {
            }
            //end sendEmail

            return $this->render(":default:password-code-sent.html.twig",
                [
                    'user' => $user,
                    'information' => $this->manageInformation()
                ]);
        }
        //end send code section


        escape:
        return $this->render(":Queries:search-user.html.twig", [
            'errorMessage' => $errorMessage,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/recovery/password/{code}", name="change_recovered_password", defaults={"code"=null})
     **/
    public function recoverPasswordFromCodeAction(Request $request, $code)
    {
        $error = null;
        $codeGet = $request->get('secretCode');
        if ($code == null && $codeGet != null) {
            return $this->redirectToRoute("change_recovered_password", ['code' => $codeGet]);
        }
        $codeClass = $this->getDoctrine()->getRepository(PasswordRecovery::class)->findOneBy(
            [
                'code' => $code,
                'status' => [PasswordRecovery::$OPEN, PasswordRecovery::$IN_PROGRESS]
            ]);
        if ($codeClass == null) {
            $error = "Невалиден код!";
            return $this->redirectToRoute("homepage", ['error' => $error]);
        }
        $entityManager = $this->getDoctrine()->getManager();
        if ($codeClass->getStatus() == $codeClass::$OPEN) {
            $codeClass->setStatus($codeClass::$IN_PROGRESS);
            $entityManager->merge($codeClass);
            $entityManager->flush();
        }
        $user = $this->getDoctrine()->getRepository(User::class)->find($codeClass->getUserId());

        $passwordForm = new UserPasswords();
        $form = $this->createForm(UserPasswordType::class, $passwordForm);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$passwordForm->isPasswordsEqual()) {
                $error = "Паролите не съвпадат";
                goto escape;
            }
            if (trim(strlen($passwordForm->getNewPassword())) < Constants::$passowordLen) {
                $error = "Паролите са под " . Constants::$passowordLen . " знака";
                goto escape;
            }
            $user->setPassword($this->get('security.password_encoder')->encodePassword($user, trim($passwordForm->getNewPassword())));
            $codeClass->setStatus(PasswordRecovery::$FINISHED);
            $entityManager->merge($user);
            $entityManager->merge($codeClass);
            $entityManager->flush();


            //clean old requests
            $oldRequests = $this->getDoctrine()->getRepository(PasswordRecovery::class)->findBy(
                [
                    'userId' => $user->getId(),
                    'status' => [PasswordRecovery::$OPEN, PasswordRecovery::$IN_PROGRESS]
                ]);

            $connection = $entityManager->getConnection();
            foreach ($oldRequests as $oldRequest) {
                $idToRemove = $oldRequest->getId();
                $statement = $connection->prepare("DELETE FROM password_recoveries WHERE  id = '$idToRemove'");
                $statement->execute();
            }
            $entityManager->flush();
            //end clean all old requests

            return $this->redirectToRoute("security_login");
        }


        escape:
        return $this->render(":default:change-recovered-password.html.twig",
            [
                'information' => $this->manageInformation(),
                'error' => $error,
                'form' => $form->createView(),
            ]);

    }


}