<?php


namespace App\Controller;


use App\BindingModel\EditReceiptBindingModel;
use App\BindingModel\ImageBindingModel;
use App\Constants\Constants;
use App\Entity\Receipt;
use App\Exception\InternalRestException;
use App\Exception\NotFoundException;
use App\Form\CreateReceiptType;
use App\Service\LocalLanguage;
use App\Service\ReceiptService;
use App\Utils\Pageable;
use App\ViewModel\BrowseRecipesViewModel;
use App\ViewModel\ReceiptDetailsViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReceiptController extends BaseController
{

    private $receiptService;

    private $validator;

    public function __construct(LocalLanguage $language,
                                ReceiptService $receiptService,
                                ValidatorInterface $validator)
    {
        parent::__construct($language);
        $this->receiptService = $receiptService;
        $this->validator = $validator;
    }

    /**
     * @Route("/recipes", name="show_recipes")
     * @param Request $request
     * @return Response
     */
    public function showRecipes(Request $request): Response
    {
        $recipes = $this->receiptService->findAll(new Pageable($request));

        return $this->render('menu/browse-recipes.html.twig', [
            'viewModel' => new BrowseRecipesViewModel($recipes)
        ]);
    }

    /**
     * @Route("/receipt/{id}", name="receipt_details", defaults={"id"=-1})
     * @param $id
     * @return Response
     * @throws NotFoundException
     */
    public function showRecipesAction($id): Response
    {
        $receipt = $this->receiptService->findOneById($id);
        if ($receipt == null)
            throw new NotFoundException($this->dictionary->productNotFound());

        return $this->render("menu/receipt.html.twig", [
            'viewModel' => new ReceiptDetailsViewModel($receipt)
        ]);
    }

    /**
     * @Route("/admin/receipt/edit/{id}", name="edit_receipt", defaults={"id" = -1})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     * @throws NotFoundException
     * @throws InternalRestException
     */
    public function editReceiptAction(Request $request, int $id)
    {
        $receipt = $this->receiptService->findOneById($id, true);
        if ($receipt == null)
            throw new NotFoundException($this->dictionary->productNotFound());

        $bindingModel = new EditReceiptBindingModel();
        $form = $this->createForm(CreateReceiptType::class, $bindingModel);
        $form->handleRequest($request);

        $err = null;
        if ($form->isSubmitted() && count($this->validator->validate($bindingModel)) < 1) {
            $this->validateToken($request);
            if ($bindingModel->getImage() != null) {
                $imgBindingModel = ImageBindingModel::imageOverload($bindingModel->getImage());
                if (count($this->validator->validate($imgBindingModel)) > 0) {
                    $err = Constants::INVALID_IMAGE_MSG;
                    return $this->getEditReceiptView($err, $receipt, $form);
                }
            }

            $receipt = $this->receiptService->editReceipt($receipt, $bindingModel);
            if ($receipt->getHidden()) return $this->redirectToRoute('admin_panel');

            return $this->redirectToRoute('receipt_details', ['id' => $receipt->getId()]);
        }

        return $this->getEditReceiptView($err, $receipt, $form);
    }

    private function getEditReceiptView(?string $err, Receipt $receipt, FormInterface $form): Response
    {
        return $this->render("admins/recipes/edit-receipt.html.twig", [
            'error' => $err,
            'model' => $receipt,
            'form1' => $form->createView(),
        ]);
    }
}