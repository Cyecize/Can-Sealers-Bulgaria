<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 10:08 AM
 */

namespace App\Controller;

use App\BindingModel\NotificationBindingModel;
use App\Exception\IllegalArgumentException;
use App\Exception\InternalRestException;
use App\Form\NotificationType;
use App\Service\LocalLanguage;
use App\Service\NotificationSendingService;
use App\Service\RoleService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @var NotificationSendingService
     */
    private $notificationService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(LocalLanguage $language,
                                UserService $userService,
                                RoleService $roleService,
                                NotificationSendingService $notificationService,
                                ValidatorInterface $validator)
    {
        parent::__construct($language);
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->notificationService = $notificationService;
        $this->validator = $validator;
    }

    /**
     * @Route("/admin/panel", name="admin_panel")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function adminPanelAction(Request $request)
    {
        return $this->render('admins/admin-panel.html.twig', [
            'info' => $request->get('info')
        ]);
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
     * @Route("/admin/users/notify", name="notify_all_users")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function notifyUsersAction(Request $request)
    {
        $bindingModel = new NotificationBindingModel();
        $form = $this->createForm(NotificationType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (count($this->validator->validate($bindingModel)) > 0)
                goto escape;
            $this->notificationService->notifyAll($bindingModel->getMessage(), $bindingModel->getHref());
            return $this->redirectToRoute('admin_panel', ['info' => "Message was sent!"]);
        }

        escape:
        return $this->render('admins/users/send-notification.html.twig', [
            'form1' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/roles/add", name="add_role", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     * @throws \App\Exception\InternalRestException
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
        return new JsonResponse(['message' => "Role added"]);
    }

    /**
     * @Route("/admin/roles/remove", name="remove_role", methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     * @throws \App\Exception\InternalRestException
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
        return new JsonResponse(['message' => "Role removed"]);
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
        return ['user' => $user, 'role' => $role];
    }
}