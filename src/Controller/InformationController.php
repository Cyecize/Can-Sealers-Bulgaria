<?php

namespace App\Controller;

use App\BindingModel\AboutUsBindingModel;
use App\BindingModel\CharacteristicBindingModel;
use App\BindingModel\ContactsBindingModel;
use App\BindingModel\SocialLinkBindingModel;
use App\Exception\IllegalArgumentException;
use App\Exception\InternalRestException;
use App\Form\AboutUsType;
use App\Form\CharacteristicType;
use App\Form\ContactInfoType;
use App\Form\SocialLinkType;
use App\Service\CharacteristicsYamlService;
use App\Service\ContactsYamlService;
use App\Service\LocalLanguage;
use App\Service\SocialLinksYamlService;
use App\Service\WebsiteInfoYamlService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InformationController extends BaseController
{
    private const INVALID_SOCIAL_LINKS_FORM = "Невалидни данни за формата";
    private const INVALID_ABOUT_US = "Не оставайте празно полето!";

    /**
     * @var SocialLinksYamlService
     */
    private $socialLinkService;

    /**
     * @var WebsiteInfoYamlService
     */
    private $websiteInfoService;

    /**
     * @var ContactsYamlService
     */
    private $contactsService;

    /**
     * @var CharacteristicsYamlService
     */
    private $characteristicsService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(LocalLanguage $language,
                                SocialLinksYamlService $socialLinkService,
                                WebsiteInfoYamlService $websiteInfoService,
                                ContactsYamlService $contactsService,
                                CharacteristicsYamlService $characteristicsService,
                                ValidatorInterface $validator)
    {
        parent::__construct($language);
        $this->socialLinkService = $socialLinkService;
        $this->websiteInfoService = $websiteInfoService;
        $this->contactsService = $contactsService;
        $this->characteristicsService = $characteristicsService;
        $this->validator = $validator;
    }

    /**
     * @Route("/address-phone-mail", name="get_address_phone_mail")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws InternalRestException
     */
    public function addressPhoneMailAction(Request $request)
    {
        $bindingModel = new ContactsBindingModel();
        $form = $this->createForm(ContactInfoType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validator->validate($bindingModel)) > 0)
                return $this->redirectToRoute("homepage", ['error' => "Не оставайте полетата празни!"]);
            $this->contactsService->saveSettings($bindingModel);
            $this->websiteInfoService->saveAddress($bindingModel);
            return $this->redirectToRoute("homepage");
        }

        return $this->render("/queries/mid-information.html.twig");
    }

    /**
     * @Route("/edit-about", name="get_about-edit")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws InternalRestException
     */
    public function aboutEditAction(Request $request)
    {
        $aboutUs = $this->websiteInfoService->getWebsiteInfoView()->getAboutUs();
        $bindingModel = new AboutUsBindingModel();
        $form = $this->createForm(AboutUsType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validator->validate($bindingModel)) > 0)
                return $this->redirectToRoute("homepage", ['error' => self::INVALID_ABOUT_US]);
            $this->websiteInfoService->saveAboutUs($bindingModel);
            return $this->redirectToRoute("homepage");
        }

        return $this->render("queries/about-edit.html.twig", [
            'aboutUs' => $aboutUs,
        ]);
    }

    /**
     * @Route("/edit-social/{id}", name="edit_social", defaults={"id" = null})
     * @Security("has_role('ROLE_ADMIN')"))
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     * @throws InternalRestException
     * @throws \App\Exception\IllegalArgumentException
     */
    public function socialEditAction(Request $request, $id)
    {
        $social = $this->socialLinkService->findOneById(intval($id));
        if ($social == null)
            throw new InternalRestException("link not found");
        $bindingModel = new SocialLinkBindingModel();
        $form = $this->createForm(SocialLinkType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validator->validate($bindingModel)) > 0)
                return $this->redirectToRoute('homepage', ['error' => self::INVALID_SOCIAL_LINKS_FORM]);
            $this->socialLinkService->save($bindingModel);
            return $this->redirectToRoute("homepage");
        }
        return $this->render("queries/social-edit.html.twig", [
            'form' => $form->createView(),
            'social' => $social,
        ]);
    }

    /**
     * @Route("/characteristic/{id}", name="get_characteristic", defaults={"id"= null})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     * @throws InternalRestException
     * @throws IllegalArgumentException
     */
    public function characteristicAction(Request $request, $id)
    {
        $error = null;
        $characteristic = $this->characteristicsService->findOneById($id);
        if ($characteristic == null)
            throw new InternalRestException("Characteristic not found!");

        $bindingModel = new CharacteristicBindingModel();
        $form = $this->createForm(CharacteristicType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validator->validate($bindingModel)) > 0)
                return $this->redirectToRoute("homepage", ["error" => "Въведените данни не са валидни! Не оставяйте празни полета!"]);
            $this->characteristicsService->saveCharacteristic($bindingModel);
            return $this->redirectToRoute("homepage");
        }

        return $this->render("queries/characteristics.html.twig", [
            'charr' => $characteristic,
            'error' => $error,
            'form' => $form->createView(),
        ]);

    }
}
