<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 3:59 PM
 */

namespace AppBundle\Controller;

use AppBundle\Constants\Roles;
use AppBundle\Exception\InternalRestException;
use AppBundle\Service\Lang\ILanguagePack;
use AppBundle\Service\LocalLanguage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends Controller
{
    private const INVALID_TOKEN = "Invalid token";

    /**
     * @var string
     */
    protected $currentLang;

    /**
     * @var LocalLanguage
     */
    protected $language;

    /**
     * @var ILanguagePack
     */
    protected $dictionary;

    public function __construct(LocalLanguage $language)
    {
        $this->currentLang = $language->getLocalLang();
        $this->language = $language;
        $this->dictionary = $language->dictionary();
    }

    protected function isUserLogged(): bool
    {
        return $this->get('security.authorization_checker')->isGranted(Roles::ROLE_USER);  //when user is logged
    }

    protected function isAdminLogged(): bool
    {
        return $this->get('security.authorization_checker')->isGranted(Roles::ROLE_ADMIN, 'ROLES');
    }

    protected function getUserId(): int
    {
        return $this->getUser()->getId();
    }

    /**
     * @param $bindingModel
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function validate($bindingModel)
    {
        return $this->get('validator')->validate($bindingModel);
    }

    /**
     * @param Request $request
     * @throws InternalRestException
     */
    protected function validateToken(Request $request)
    {
        $token = $request->get('token');
        if (!$this->isCsrfTokenValid('token', $token))
            throw new InternalRestException(self::INVALID_TOKEN);
    }
}