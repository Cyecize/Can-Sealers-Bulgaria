<?php

namespace AppBundle\Controller;

use AppBundle\Constants\Constants;
use AppBundle\Entity\Gallery;
use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductCategory;
use AppBundle\Exception\NotFoundException;
use AppBundle\Form\ImageType;
use AppBundle\Form\ProductCategoryType;
use AppBundle\Form\ProductType;
use AppBundle\Service\CategoryService;
use AppBundle\Service\LocalLanguage;
use AppBundle\Service\ProductService;
use AppBundle\Utils\CharacterTranslator;
use AppBundle\Utils\DirectoryCreator;
use AppBundle\Utils\ImageCompressorManager;
use AppBundle\Utils\ImgCompressor;
use AppBundle\Utils\PageRequest;
use AppBundle\ViewModel\ProductDetailsViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends BaseController
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(LocalLanguage $language, ProductService $productService, CategoryService $categoryService)
    {
        parent::__construct($language);
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/product/{prodId}", name="product_details", defaults={"prodId"=0})
     * @param $prodId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @Route("/addImage/category/{categoryName}/product/{id}", name="add_image_to_gallery", defaults={"id" = null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function addImageToGalleryAction(Request $request, $categoryName, $id)
    {
        if ($id == null || $categoryName == null)
            return $this->redirectToRoute("homepage");
        $information = $this->manageInformation();
        $prodRepo = $this->getDoctrine()->getRepository(Product::class);
        $prod = $prodRepo->find($id);
        if ($prod == null || $prod->getCategory()->getCategoryName() != $categoryName)
            return $this->redirectToRoute("homepage");
        //http Checking ends here
        $error = $request->get('error');

        $imageToUpload = new Image();
        $form = $this->createForm(ImageType::class, $imageToUpload);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            //text validating
            if ($imageToUpload->getAltMessage() == null) {
                $error = "Не оставяйте описанието празно";
                goto escape;
            }
            //end text validating

            //image validating
            $imageName = $_FILES['image']['name']['img_file'];
            $tmpImgName = $_FILES['image']['tmp_name']['img_file'];

            if (!ImageCompressorManager::isValidImage('img_file')) {
                $error = "Невалиден файл!";
                goto  escape;
            }

            $imageSize = $_FILES['image']['size']['img_file'];
            if ($imageSize > Constants::$maxUploadSize) {
                $error = "Файлът не трябва да надвишава 6MB.";
                goto escape;
            }

            $tempDestination = Constants::$temporaryUploadFolder . $imageName;
            move_uploaded_file($tmpImgName, $tempDestination);

            DirectoryCreator::createGalleryDirectory($prod->getId()); //creates Dir for gallery if not present

            //compressign image
            $setting = ImageCompressorManager::createSettingsForImage(Constants::$temporaryOutputFolder);
            $compressor = new ImgCompressor($setting);
            $compressedImgName = ImageCompressorManager::compress($compressor, $tempDestination);
            //end compressing

            copy(Constants::$temporaryOutputFolder . $compressedImgName, Constants::$galleryPath . $prod->getId() . DIRECTORY_SEPARATOR . $compressedImgName);
            unlink($tempDestination);  //erase temp file
            unlink(Constants::$temporaryOutputFolder . $compressedImgName); //erase temp output file
            //end image validating

            $imageToUpload->setImageUrl($compressedImgName);
            $doctrine = $this->getDoctrine();
            $entityManager = $doctrine->getManager();
            //create gallery if it does not exist
            $galleryRepo = $doctrine->getRepository(Gallery::class);
            $gallery = $galleryRepo->findOneBy(array("productId" => $prod->getId()));
            if ($gallery == null) {
                $gallery = new Gallery();
                $gallery->setProductId($prod->getId());
                $gallery->setCategoryId($prod->getCategoryId());
                $entityManager->persist($gallery);
                $entityManager->flush();
                $gallery = $galleryRepo->findOneBy(array("productId" => $prod->getId())); //now i have the ID fo show
            }
            //end create gallery if not exists
            $imageToUpload->setGalleryId($gallery->getId());
            $imageToUpload->setProductId($prod->getId());

            $this->getDoctrine()->getManager()->persist($imageToUpload);
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute("show_product", [
                'categoryName' => $prod->getCategory()->getCategoryName(),
                'id' => $prod->getId(),
            ]);

        }

        escape:

        return $this->render("products/add-image-to-gallery.html.twig", [
            'information' => $information,
            'product' => $prod,
            'form' => $form->createView(),
            'error' => $error,
            'imageToUpload' => $imageToUpload,
        ]);
    }

    /**
     * @Route("/removeImage/imageId/{id}", name="remove_image_from_gallery", defaults={"id" = null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function removeImageAction($id)
    {
        $currentUser = $this->getUser();
        if ($id == null || !$currentUser->getAdminStatus()) {
            return $this->redirectToRoute("homepage");
        }
        $imgRepo = $this->getDoctrine()->getRepository(Image::class);
        if ($id == null)
            return $this->redirectToRoute("homepage");
        $image = $imgRepo->find($id);
        if ($image == null)
            return $this->redirectToRoute("homepage");
        unlink($image->getImageUrl()); //imageName contains the full path
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($image);
        $entityManager->flush();

        $prod = $this->getDoctrine()->getRepository(Product::class)->find($image->getProductId());
        return $this->redirectToRoute("show_product", [
            'categoryName' => $prod->getCategory()->getCategoryName(),
            'id' => $image->getProductId(),
        ]);
    }

    /**
     * @Route("/product-edit/{id}", name="edit_product", defaults={"id" = null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function productEditAction(Request $request, $id)
    {
        if (!$this->getUser()->getAdminStatus() || $id == null)
            return $this->redirectToRoute("homepage");
        $productRepo = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepo->find($id);
        if ($product == null)
            return $this->redirectToRoute("homepage");
        $error = null;

        $editedProduct = new Product();
        $form = $this->createForm(ProductType::class, $editedProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$editedProduct->isValid())
                return $this->redirectToRoute("show_product", ['id' => $id, 'categoryName' => $product->getCategory()->getCategoryName(), 'error' => "Празни полета!"]);

            $entityManager = $this->getDoctrine()->getManager();
            $product->setAltMessage($editedProduct->getAltMessage());
            $product->setProductName($editedProduct->getProductName());
            $product->setProductDescription($editedProduct->getProductDescription());
            $product->setPrice($editedProduct->getPrice());
            $product->setTaxClear($editedProduct->isTaxClear());
            $product->setHidden($editedProduct->isHidden());
            $product->setProductType($editedProduct->getProductType());
            $entityManager->merge($product);
            $entityManager->flush();


            $imageName = $_FILES['image']['name']['img_file'];
            $tmpImgName = $_FILES['image']['tmp_name']['img_file'];
            if ($imageName != null) { //image is posted
                if (!ImageCompressorManager::isValidImage('img_file'))
                    return $this->redirectToRoute("show_product", ['id' => $id, 'categoryName' => $product->getCategory()->getCategoryName(), 'error' => "Снимката не е валидна"]);

                $imageSize = $_FILES['image']['size']['img_file'];
                if ($imageSize > Constants::$maxUploadSize) {
                    $error = "Файлът не трябва да надвишава 6MB., тази е " . round($imageSize / 1024 / 1024, 2);
                    return $this->redirectToRoute("show_product", ['id' => $id, 'categoryName' => $product->getCategory()->getCategoryName(), 'error' => $error]);
                }

                $tempDestination = Constants::$temporaryUploadFolder . $imageName;
                move_uploaded_file($tmpImgName, $tempDestination);

                //compressing img
                $setting = ImageCompressorManager::createSettingsForImage(Constants::$temporaryOutputFolder);
                $compressor = new ImgCompressor($setting);
                $compressedImgName = ImageCompressorManager::compress($compressor, $tempDestination);
                //end compressing

                copy(Constants::$temporaryOutputFolder . $compressedImgName, Constants::$categoriesPath . $product->getCategory()->getImagesPath() . DIRECTORY_SEPARATOR . $compressedImgName);
                unlink($tempDestination);  //erase temp file
                unlink(Constants::$temporaryOutputFolder . $compressedImgName); //erase temp output file

                $oldImageName = $product->getImgPath();
                $product->setImgPath($compressedImgName);
                $entityManager->merge($product);
                $entityManager->flush();
                try {
                    unlink(Constants::$categoriesPath . $product->getCategory()->getImagesPath() . DIRECTORY_SEPARATOR . $oldImageName);
                } catch (\Exception $e) {
                }

            }

            return $this->redirectToRoute("show_product", ['id' => $id, 'categoryName' => $product->getCategory()->getCategoryName()]);
        }

        escape:

        return $this->render(":Queries:product-edit.html.twig", [
            'product' => $product,
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/category-create/{parentId}", name="create-category", defaults={"parentId"=null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */

    public function categoryCreateAction(Request $request, $parentId)
    {
        if ($parentId == null || $parentId == "undefined") //javaScript hell JEA
            $parentId = 0;

        if (!$this->getUser()->getAdminStatus())
            return $this->redirectToRoute("homepage");
        $error = null;

        $category = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($category->getCategoryName() == null || strlen(trim($category->getCategoryName())) > 60)
                return $this->redirectToRoute("show_categories", ['error' => "Полето не е валидно!"]);

            $translator = new CharacterTranslator();
            $category->setImagesPath($translator->convertFromCyrilicToLatin($category->getCategoryName()));
            DirectoryCreator::createCategoryDirectory($category->getImagesPath());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute("show_categories");
        }

        return $this->render(":Queries:category-create.html.twig",
            [
                'form' => $form->createView(),
                'parentId' => $parentId,
            ]);
    }

    /**
     * @Route("/product-create/category/{catId}", name="create_product", defaults={"catId" = null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function createProductAction(Request $request, $catId)
    {
        if (!$this->getUser()->getAdminStatus() || $catId == null)
            return $this->redirectToRoute("homepage");

        $error = $request->get("error");
        $information = $this->manageInformation();
        $categoryRepo = $this->getDoctrine()->getRepository(ProductCategory::class);
        $category = $categoryRepo->find($catId);
        if ($category == null)
            return $this->redirectToRoute("homepage");

        $product = new Product();
        $product->setCategoryId($category->getId());
        $product->setCategory($category);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$product->isValid()) {
                $error = "Попълнете всички полета";
                goto  escape;
            }

            $imageName = $_FILES['image']['name']['img_file'];
            $tmpImgName = $_FILES['image']['tmp_name']['img_file'];
            if ($imageName == null || (!ImageCompressorManager::isValidImage('img_file'))) {
                $error = "Имаше проблем с качването на снимката! Изберете Jpg или Png с разбер до " . Constants::$maxUploadSize;
                goto escape;
            }

            $imageSize = $_FILES['image']['size']['img_file'];
            if ($imageSize > Constants::$maxUploadSize) {
                $error = "Файлът не трябва да надвишава 6MB., тoзи е " . round($imageSize / 1024 / 1024, 2);
                goto escape;
            }

            $tempDestination = Constants::$temporaryUploadFolder . $imageName;
            move_uploaded_file($tmpImgName, $tempDestination);

            //compressing
            $setting = ImageCompressorManager::createSettingsForImage(Constants::$temporaryOutputFolder);
            $compressor = new ImgCompressor($setting);
            $compressedImgName = ImageCompressorManager::compress($compressor, $tempDestination);
            //end compressing

            copy(Constants::$temporaryOutputFolder . $compressedImgName, Constants::$categoriesPath . $category->getImagesPath() . DIRECTORY_SEPARATOR . $compressedImgName);
            unlink($tempDestination);  //erase temp file
            unlink(Constants::$temporaryOutputFolder . $compressedImgName); //erase temp output file

            $product->setImgPath($compressedImgName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute("request_sub_category", ['catId' => $catId, 'count' => 0, 'reference' => "homepage"]);
        }

        escape:
        return $this->render(":products:add-product.html.twig", [
            'information' => $information,
            'category' => $category,
            'error' => $error,
            'form' => $form->createView(),
            'product' => $product,
        ]);

    }

    /**
     * @Route("/category/rename/{catId}", name="rename_category", defaults={"catId" = null})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function renameCategoryAction(Request $request, $catId)
    {
        if (!$this->getUser()->getAdminStatus() || $catId == null)
            return $this->redirectToRoute("homepage");
        $catRepo = $this->getDoctrine()->getRepository(ProductCategory::class);
        $category = $catRepo->find($catId);
        if ($category == null)
            return $this->redirectToRoute("homepage");

        $editedCat = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $editedCat);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($editedCat->getCategoryName() == null)
                return $this->redirectToRoute("show_categories");
            $translator = new CharacterTranslator();
            $newLatin = $translator->convertFromCyrilicToLatin($editedCat->getCategoryName());
            try {
                rename(Constants::$categoriesPath . $category->getImagesPath(), Constants::$categoriesPath . $newLatin);
            } catch (\Exception $e) {
            }
            $category->setCategoryName($editedCat->getCategoryName());
            $category->setImagesPath($newLatin);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($category);
            $entityManager->flush();

            return $this->redirectToRoute("show_categories");
        }

        return $this->render(":Queries:category-rename.html.twig",
            [
                'category' => $category,
                'form' => $form->createView(),
            ]);
    }

    /**
     * finds all sub categories for a given productCategory
     *
     * @param ProductCategory $category
     * @return ProductCategory
     */
    private function fetchSubCategories(ProductCategory $category): ProductCategory
    {

        $repo = $this->getDoctrine()->getRepository(ProductCategory::class);
        $subCats = $repo->findBy(array('parentId' => $category->getId()));
        $category->setSubcategories($subCats);
        return $category;
    }

}
