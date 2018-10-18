<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/18/2018
 * Time: 4:24 PM
 */

namespace AppBundle\Controller;


use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends BaseController
{
    public function __construct(LocalLanguage $language)
    {
        parent::__construct($language);
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
}