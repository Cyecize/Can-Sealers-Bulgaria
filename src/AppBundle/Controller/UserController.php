<?php
/**
 * Created by PhpStorm.
 * User: Cyecize
 * Date: 12/4/2017
 * Time: 5:11 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\ChangePasswordBindingModel;
use AppBundle\BindingModel\PersonalInfoBindingModel;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\NotFoundException;
use AppBundle\Form\EditPasswordType;
use AppBundle\Form\PersonalInfoType;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    private const USER_NOT_FOUND_MSG = "User not found!";

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
     * @Route("/user/show/{username}", name="show_user", defaults={"username":null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $username
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     */
    public function userDetailsAction($username){
        $user = $this->userService->findOneByUsername($username);
        if($user == null)
            throw new NotFoundException(self::USER_NOT_FOUND_MSG);
        return $this->render('menu/show-user.html.twig', [
            'user'=>$user,
        ]);
    }

    /**
     * @Route("/profile", name="my_profile")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
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
     * @Route("/user/edit/password", name="edit_password")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function editPasswordAction(Request $request)
    {
        $bindingModel = new ChangePasswordBindingModel();
        $form = $this->createForm(EditPasswordType::class, $bindingModel);
        $form->handleRequest($request);

        $error = null;
        if ($form->isSubmitted() && count($this->validate($bindingModel)) < 1) {
            $this->validateToken($request);
            try {
                $this->userService->changePassword($this->userService->findOneById($this->getUserId()), $bindingModel);
                return $this->redirectToRoute('security_logout');
            } catch (IllegalArgumentException $e) {
                $error = $this->language->forName($e->getMessage());
            }
        }
        escape:
        return $this->render(':users/settings:change-password.html.twig', [
            'form1' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/user/edit/info", name="edit_personal_info")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function editPersonalInfoAction(Request $request)
    {
        $bindingModel = new PersonalInfoBindingModel();
        $form = $this->createForm(PersonalInfoType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && count($this->validate($bindingModel)) < 1) {
            $this->validateToken($request);
            $this->userService->editUserInfo($this->userService->findOneById($this->getUserId()), $bindingModel);
            return $this->redirectToRoute('my_profile');
        }

        return $this->render('users/settings/edit-personal-info.html.twig', [
            'form1' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/account/remove", name="remove_account", defaults={"username"=null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws IllegalArgumentException
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function removeUserAction(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $userId = $this->getUserId(); //TODO !This is important since after invalidating session userId will be null
            $this->validateToken($request);
            $this->get('security.token_storage')->setToken(null);
            $this->get('session')->invalidate();
            $this->userService->removeAccount($this->userService->findOneById($userId));
            return $this->redirectToRoute('homepage');
        }
        return $this->render('users/settings/remove-profile.html.twig');
    }
}