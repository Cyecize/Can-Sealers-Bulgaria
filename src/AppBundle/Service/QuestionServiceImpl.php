<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 1:33 PM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\QuestionBindingModel;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use AppBundle\Repository\QuestionRepository;
use AppBundle\Utils\ModelMapper;
use AppBundle\Utils\Page;
use AppBundle\Utils\Pageable;
use Doctrine\ORM\EntityManagerInterface;

class QuestionServiceImpl implements QuestionService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository|QuestionRepository
     */
    private $questionRepo;

    /**
     * @var ModelMapper
     */
    private $modelMapper;

    public function __construct(EntityManagerInterface $entityManager, ModelMapper $modelMapper)
    {
        $this->entityManager = $entityManager;
        $this->modelMapper = $modelMapper;
        $this->questionRepo = $entityManager->getRepository(Question::class);
    }

    public function createQuestion(QuestionBindingModel $bindingModel, User $user = null): Question
    {
        $question = $this->modelMapper->map($bindingModel, Question::class);
        $question->setUser($user);
        $this->entityManager->persist($question);
        $this->entityManager->flush();
        return $question;
    }

    public function findOneById(int $id): ?Question
    {
        return $this->questionRepo->find($id);
    }

    public function findAll(Pageable $pageable): Page
    {
        return $this->questionRepo->findPage($pageable);
    }

    public function all(): array
    {
        return $this->questionRepo->findBy(array(), array('date' => 'DESC'));
    }
}