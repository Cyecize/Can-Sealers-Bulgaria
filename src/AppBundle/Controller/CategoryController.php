<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/9/2018
 * Time: 3:01 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\CategoryBindingModel;
use AppBundle\Entity\ProductCategory;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\NotFoundException;
use AppBundle\Form\CategoryType;
use AppBundle\Service\CategoryService;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\ProductService;
use AppBundle\Utils\Pageable;
use AppBundle\ViewModel\BrowseProductsViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(LocalLanguage $language, CategoryService $categoryService, ProductService $productService)
    {
        parent::__construct($language);
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    /**
     * @Route("/categories", name="show_categories")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoriesAction(Request $request)
    {
        $categories = $this->categoryService->findMain();
        $cat = new ProductCategory();
        $cat->setCategoryName($this->dictionary->all());
        $cat->setLatinName($this->dictionary->all());
        return $this->render(":menu:browse-products.html.twig", [
            'viewModel' => new BrowseProductsViewModel($cat, $this->productService->findAll(new Pageable($request)), $categories),
        ]);
    }

    /**
     * @Route("/category/{catName}", name="category_details", defaults={"catName"=""})
     * @param Request $request
     * @param $catName
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     */
    public function categoryDetailsAction(Request $request, $catName)
    {
        $category = $this->categoryService->findOneByName($catName);
        if ($category == null)
            throw new NotFoundException(sprintf($this->dictionary->categoryNotFoundFormat(), $catName));

        return $this->render("menu/browse-products.html.twig",
            [
                'viewModel' => new BrowseProductsViewModel($category, $this->productService->findByCategoryRecursive($category, new Pageable($request)), $category->getSubcategories()),
            ]);
    }

    /**
     * @Route("/admin/category/create", name="create_category")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function createCategoryAction(Request $request)
    {
        $bindingModel = new CategoryBindingModel();
        $form = $this->createForm(CategoryType::class, $bindingModel);
        $form->handleRequest($request);

        $err = null;
        if ($form->isSubmitted() && count($this->validate($bindingModel)) < 1) {
            $this->validateToken($request);
            try {
                $category = $this->categoryService->createCategory($bindingModel);
                return $this->redirectToRoute('category_details', ['catName' => $category->getCategoryName()]);
            } catch (IllegalArgumentException $e) {
                $err = $this->language->forName($e->getMessage());
            }
        }

        return $this->render('admins/categories/add-category.html.twig', [
            'categories' => $this->categoryService->findAll(),
            'form1' => $form->createView(),
            'error' => $err,
            'model' => $bindingModel,
        ]);
    }

    /**
     * @Route("/admin/category/edit/{catId}", name="edit_category", defaults={"catId" = null})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param $catId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundException
     * @throws \AppBundle\Exception\InternalRestException
     */
    public function editCategoryAction(Request $request, $catId)
    {
        $category = $this->categoryService->findOneById($catId);
        if($category == null)
            throw new NotFoundException(sprintf($this->dictionary->categoryNotFoundFormat(), $catId));
        $err = null;

        $bindingModel = new CategoryBindingModel();
        $form = $this->createForm(CategoryType::class, $bindingModel);
        $form->handleRequest($request);

        if($form->isSubmitted() && count($this->validate($bindingModel)) < 1){
            $this->validateToken($request);
            try {
                $category = $this->categoryService->editCategory($category,$bindingModel);
                return $this->redirectToRoute('category_details', ['catName' => $category->getCategoryName()]);
            } catch (IllegalArgumentException $e) {
                $err = $this->language->forName($e->getMessage());
            }
        }

        return $this->render('admins/categories/add-category.html.twig', [
            'categories' => $this->categoryService->findAll(),
            'form1' => $form->createView(),
            'error' => $err,
            'model' => $category,
        ]);
    }

}