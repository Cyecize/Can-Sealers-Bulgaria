<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 10:08 AM
 */

namespace AppBundle\Controller;

use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\InternalRestException;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\RoleService;
use AppBundle\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseController
{
    private const INVALID_FORM_PARAMETERS = "Invalid form parameters!";

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var RoleService
     */
    private $roleService;

    public function __construct(LocalLanguage $language, UserService $userService, RoleService $roleService)
    {
        parent::__construct($language);
        $this->userService = $userService;
        $this->roleService = $roleService;
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

    /**
     * @Route("/admin/roles/add", name="add_role", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function addRoleAction(Request $request)
    {
        $this->validateToken($request);
        $bind = $this->bindRoleRequest($request);
        try {
            $this->userService->addRole($bind['user'], $bind['role']);
        } catch (IllegalArgumentException $e) {
            throw new InternalRestException($e->getMessage());
        }
        return new JsonResponse(['message'=>"Role added"]);
    }

    /**
     * @Route("/admin/roles/remove", name="remove_role", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function removeRoleAction(Request $request)
    {
        $this->validateToken($request);
        $bind = $this->bindRoleRequest($request);
        try {
            $this->userService->removeRole($bind['user'], $bind['role']);
        } catch (IllegalArgumentException $e) {
            throw new InternalRestException($e->getMessage());
        }
        return new JsonResponse(['message'=>"Role removed"]);
    }

    //private

    /**
     * @param Request $request
     * @return array
     * @throws InternalRestException
     */
    private function bindRoleRequest(Request $request): array
    {
        $roleType = $request->get('roleType');
        $userId = $request->get('userId');
        if ($roleType == null || $userId == null)
            throw new InternalRestException(self::INVALID_FORM_PARAMETERS);
        $user = $this->userService->findOneById(intval($userId));
        $role = $this->roleService->findByRoleName($roleType);
        if ($user == null || $role == null)
            throw new InternalRestException(self::INVALID_FORM_PARAMETERS);
        return ['user'=>$user, 'role'=>$role];
    }
}