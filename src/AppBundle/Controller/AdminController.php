<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 10:08 AM
 */

namespace AppBundle\Controller;

use AppBundle\Service\LocalLanguage;
use AppBundle\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseController
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
     * @Route("/admin/panel", name="admin_panel")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminPanelAction(){
        return $this->render('admins/admin-panel.html.twig');
    }

    /**
     * @Route("/admin/users/all", name="users_observe")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function observeUsersAction()
    {
        $users = $this->userService->findAll();
        return $this->render('admins/users/all-users.html.twig', [
            'users' => $users,
        ]);
    }
}