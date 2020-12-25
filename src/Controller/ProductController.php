<?php

namespace App\Controller;

use App\BindingModel\CreateProductBindingModel;
use App\BindingModel\EditProductBindingModel;
use App\BindingModel\ImageBindingModel;
use App\Entity\Gallery;
use App\Exception\NotFoundException;
use App\Form\CreateProductType;
use App\Service\CategoryService;
use App\Service\GalleryService;
use App\Service\LocalLanguage;
use App\Service\ProductService;
use App\Utils\PageRequest;
use App\ViewModel\ProductDetailsViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ProductController extends BaseController
{
    private const INVALID_IMAGE_MSG = "Invalid Image Type or Size > 2MB";

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var GalleryService
     */
    private $galleryService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(LocalLanguage $language,
                                ProductService $productService,
                                CategoryService $categoryService,
                                GalleryService $galleryService,
                                ValidatorInterface $validator)
    {
        parent::__construct($language);
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->galleryService = $galleryService;
        $this->validator = $validator;
    }

    /**
     * @Route("/product/{prodId}", name="product_details", defaults={"prodId"=0})
     * @param $prodId
     * @return RedirectResponse|Response
     * @throws NotFoundException
     */
    public function showProductAction($prodId)
    {
        $prod = $this->productService->findOneById($prodId);
        if ($prod == null)
            throw new NotFoundException($this->dictionary->productNotFound());
        $galleryRepo = $this->getDoctrine()->getRepository(Gallery::class);
        $gallery = $galleryRepo->findOneBy(array("product" => $prod));

        return $this->render("menu/product.html.twig", [
            'viewModel' => new ProductDetailsViewModel($prod, $this->categoryService->findMain(), $this->productService->findByCategory($prod->getCategory(), new PageRequest(1, 5)), $gallery)
        ]);
    }

    /**
     * @Route("/admin/products/browse", name="admin_browse_products")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showAllProductsAction()
    {
        return $this->render('admins/products/all-products.html.twig', [
            'products' => $this->productService->all()
        ]);
    }

    /**
     * @Route("/admin/product/edit/{id}", name="edit_product", defaults={"id" = null})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     * @throws NotFoundException
     * @throws \App\Exception\InternalRestException
     */
    public function editProductAction(Request $request, $id)
    {
        $product = $this->productService->findOneById(intval($id), true);
        if ($product == null)
            throw new NotFoundException($this->dictionary->productNotFound());

        $bindingModel = new EditProductBindingModel();
        $form = $this->createForm(CreateProductType::class, $bindingModel);
        $form->handleRequest($request);

        $err = null;
        if ($form->isSubmitted() && count($this->validator->validate($bindingModel)) < 1) {
            $this->validateToken($request);
            if ($bindingModel->getImage() != null) {
                $imgBindingModel = ImageBindingModel::imageOverload($bindingModel->getImage());
                if (count($this->validator->validate($imgBindingModel)) > 0) {
                    $err = self::INVALID_IMAGE_MSG;
                    goto escape;
                }
            }
            $cat = $this->categoryService->findOneById($bindingModel->getCategoryId());
            if ($cat == null)
                throw new NotFoundException(sprintf($this->dictionary->categoryNotFoundFormat(), $bindingModel->getCategoryId()));
            $product = $this->productService->editProduct($product, $bindingModel, $cat);
            if ($product->isHidden())
                return $this->redirectToRoute('admin_panel');
            return $this->redirectToRoute('product_details', ['prodId' => $product->getId()]);
        }

        escape:

        return $this->render("admins/products/edit-product.html.twig", [
            'error' => $err,
            'model' => $product,
            'form1' => $form->createView(),
            'categories' => $this->categoryService->findAll(),
            'gallery' => $this->galleryService->findByProduct($product)
        ]);
    }

    /**
     * @Route("/admin/product/create", name="create_product")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     * @throws \App\Exception\InternalRestException
     * @throws NotFoundException
     */
    public function createProductAction(Request $request)
    {
        $bindingModel = new CreateProductBindingModel();
        $form = $this->createForm(CreateProductType::class, $bindingModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && count($this->validator->validate($bindingModel)) < 1) {
            $this->validateToken($request);
            $cat = $this->categoryService->findOneById($bindingModel->getCategoryId());
            if ($cat == null)
                throw new NotFoundException(sprintf($this->language->dictionary()->categoryNotFoundFormat(), $bindingModel->getCategoryId()));
            $prod = $this->productService->createProduct($bindingModel, $cat);
            if (!$prod->isHidden())
                return $this->redirectToRoute('product_details', ['prodId' => $prod->getId()]);
            return $this->redirectToRoute('admin_panel', ['info' => 'Product Created!']);
        }

        escape:
        return $this->render('admins/products/add-product.html.twig', [
            'form1' => $form->createView(),
            'categories' => $this->categoryService->findAll(),
            'model' => $bindingModel,
        ]);
    }
}
