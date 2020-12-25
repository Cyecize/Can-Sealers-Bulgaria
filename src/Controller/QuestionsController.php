<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 1:30 PM
 */

namespace App\Controller;

use App\BindingModel\QuestionBindingModel;
use App\Exception\InternalRestException;
use App\Exception\NotFoundException;
use App\Form\QuestionType;
use App\Service\LocalLanguage;
use App\Service\MailingService;
use App\Service\NotificationSendingService;
use App\Service\QuestionService;
use App\Service\UserService;
use App\Utils\Pageable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionsController extends BaseController
{
    private const QUESTION_NOT_FOUND_MSG = "Question not found!";

    /**
     * @var QuestionService
     */
    private $questionService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var MailingService
     */
    private $mailingService;

    /**
     * @var NotificationSendingService
     */
    private $notificationService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(LocalLanguage $language,
                                QuestionService $questionService,
                                UserService $userService,
                                MailingService $mailSendingService,
                                NotificationSendingService $notificationSendingService,
                                ValidatorInterface $validator)
    {
        parent::__construct($language);
        $this->questionService = $questionService;
        $this->userService = $userService;
        $this->mailingService = $mailSendingService;
        $this->notificationService = $notificationSendingService;
        $this->validator = $validator;
    }

    /**
     * @Route("/contacts", name = "contacts" )
     * @param Request $request
     * @return Response
     * @throws InternalRestException
     */
    public function contactsAction(Request $request)
    {
        $bindingModel = new QuestionBindingModel();
        $form = $this->createForm(QuestionType::class, $bindingModel);
        $form->handleRequest($request);

        $info = null;
        if ($form->isSubmitted() && count($this->validator->validate($bindingModel)) < 1) {
            $this->validateToken($request);
            $question = $this->questionService->createQuestion(
                $bindingModel,
                $this->userService->findOneById($bindingModel->getUserId())
            );
            $this->mailingService->sendQuestionToAdmins($question);
            $this->notificationService->onQuestion($question);
            $info = $this->dictionary->yourMessageWasSent();
            $bindingModel = new QuestionBindingModel();
        }

        return $this->render("menu/contacts.html.twig", [
            'form1' => $form->createView(),
            'about' => $request->get('about') . "\r\n",
            'questionModel' => $bindingModel,
            'info' => $info
        ]);
    }

    /**
     * @Route("/admin/questions/view/{qid}", name="question_details", defaults={"qid"="-1"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param $qid
     * @return Response
     * @throws NotFoundException
     */
    public function questionDetailsAction($qid)
    {
        $question = $this->questionService->findOneById(intval($qid));
        if ($question == null)
            throw new NotFoundException(self::QUESTION_NOT_FOUND_MSG);
        return $this->render('default/question-details.html.twig', [
            'question' => $question
        ]);
    }

    /**
     * @Route("/admin/questions/observe", name="observe_questions")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function observeQuestionsAction(Request $request)
    {
        return $this->render('admins/questions/all-questions.html.twig', [
            'page' => $this->questionService->findAll(new Pageable($request))
        ]);
    }

}